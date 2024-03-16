<?php
declare(strict_types=1);

namespace app\middlewares;

use app\models\Client;
use app\models\IotDevice;
use app\models\Log;
use app\models\User;
use app\types\AuthMethods;
use app\types\Email;
use app\types\IPv4;
use app\types\Password;
use app\types\Rol;
use app\types\UUID;
use Exception;
use InvalidArgumentException;

/**
 * Class MiddlewareUser
 *
 * Esta clase se encarga de gestionar la autenticación y autorización de los usuarios.
 * @param Rol $targetRol Rol al que se quiere acceder (ADMIN, USER, IOT, GUEST) pero es opcional por defecto es ADMIN.
 * @param int $targetId Id del usuario al que se quiere acceder, pero es opcional.
 * @returns User | False | Exception en función de la autenticación y autorización.
 */
class MiddlewareUser
{
    public UUID $bearerToken;
    public IPv4 $ipv4;
    public User $user;
    private Email $email;
    private Password $password;
    public Client $client;
    private Rol $targetRol;
    private AuthMethods $authMethod;
    private bool $debug;
    private string $headers;
    private Log $log;
    private mixed $targetId;

    /**
     * @throws Exception
     */
    public function __construct(Rol $targetRol = Rol::ADMIN, int $targetId = null)
    {


        $this->targetRol = $targetRol;
        $this->targetId = $targetId;
        $this->debug = getenv("LEAF_DEV_TOOLS") === "true";

        if ($targetRol != Rol::GUEST) {
            $this->setHeaders();
            $this->setAuthMethod();
        }

        $this->setCurrentIp();

        if ($this->targetRol != Rol::GUEST) {
            $this->setCurrentUser();
            $this->updateTokenPerIp();
        }

        if ($this->targetRol != Rol::IOT && $this->targetRol != Rol::GUEST) {
            $this->setClient();
        }

        $this->checkRol();
    }

    private function setHeaders(): void
    {
        $buffer = app()->request()->headers("Authorization");
        if ($buffer) {
            $this->headers = $buffer;
            return;
        }
        throw new InvalidArgumentException("No Authorization or unsupported header found");
    }

    private function setAuthMethod(): void
    {
        $parts = explode(" ", $this->headers);
        if ($parts[0] == "Bearer") {
            $this->bearerToken = new UUID($parts[1]);
            $this->authMethod = AuthMethods::BEARER;
        } elseif ($parts[0] == "Basic") {
            $this->authMethod = AuthMethods::BASIC;
            $credentials = base64_decode($parts[1]);
            $credentials = explode(":", $credentials);
            $this->email = new Email($credentials[0]);
            $this->password = new Password($credentials[1]);

        } else {
            throw new InvalidArgumentException("Unsupported authentication method");
        }
    }

    private function setCurrentIp(): void
    {
        $this->ipv4 = new IPv4(app()->request()->getIp());
    }

    /**
     * @throws Exception
     */
    private function setCurrentUser(): void
    {
        switch ($this->authMethod) {
            case AuthMethods::BEARER:
                $this->readBearerToken();
                if ($this->targetRol == Rol::IOT) {
                    $device = IotDevice::query()->where("token", $this->bearerToken)->first();
                    if (!$device instanceof IotDevice) {
                        throw new Exception('Device not found');
                    }
                    $user = User::query()->where("id", $device->user)->first();
                    if (!$user instanceof User) {
                        throw new Exception('User not found, provided token was: ' . $this->bearerToken);
                    }
                } else {
                    $client = Client::query()->where("token", $this->bearerToken)->first();
                    if (!$client instanceof Client) {
                        throw new Exception('Client not found, provided token was: ' . $this->bearerToken);
                    }
                    $user = Client::query()->where("id", $client->user)->first();
                    if (!$user instanceof Client) {
                        throw new Exception('User not found');
                    }
                    if ($user->locked) {
                        throw new Exception('User is locked');
                    }
                }
                $this->user = $user;
                break;
            case AuthMethods::BASIC:
                $user = User::query()->where("email", $this->email->getEmail())->first();
                if (!$user instanceof User) {
                    throw new Exception('User not found');
                }
                if ($user->locked) {
                    throw new Exception('User is locked');
                }
                if ($user->password != $this->password->getHashedPassword()) {
                    if ($this->debug) {
                        throw new Exception('Invalid password');
                    }
                    throw new Exception('Invalid email or password');
                }
                $this->user = $user;
                break;

            default:
                throw new InvalidArgumentException("Unsupported authentication method");
        }
    }

    private function readBearerToken(): void
    {
        $parts = explode(" ", $this->headers);
        $this->bearerToken = new UUID($parts[1]);
    }

    private function updateTokenPerIp(): void
    {
        $clients = Client::query()->where("user", $this->user->id)->get();
        foreach ($clients as $client) {
            if ($client->ipv4 == $this->ipv4) {
                $this->bearerToken = $client->token;
                return;
            }
        }
        $this->bearerToken = new UUID();
    }

    private function setClient(): void
    {
        $client = Client::query()->where("user", $this->user->id)->where("ipv4", $this->ipv4)->where("token", $this->bearerToken)->first();

        if ($client instanceof Client) {
            $this->client = $client;
            return;
        }

        $this->registerClient();
    }

    private function registerClient(): void
    {
        $this->client = new Client();
        $this->client->user = $this->user->id;
        $this->client->ipv4 = new IPv4(app()->request()->getIp());
        $this->client->token = $this->bearerToken;
        $this->client->save();
    }

    /**
     * @throws Exception
     */
    private function checkRol(): void
    {
        $authorized = false;

        switch ($this->targetRol) {
            case Rol::GUEST:
            case Rol::IOT;
                $authorized = true;
                break;
            case Rol::ADMIN:
                if ($this->user->rol->equals(Rol::ADMIN)) {
                    $authorized = true;
                }
                break;
            case Rol::USER:
                if ($this->user->rol->equals(Rol::ADMIN) || $this->user->rol->equals(Rol::USER)) {
                    $authorized = true;
                    return;
                }
                if ($this->targetId != null && $this->user->id === $this->targetId) {
                    $authorized = true;
                }
                if ($this->user->rol->equals(Rol::ADMIN)) {
                    $authorized = true;
                }
                break;
        }

        if (!$authorized) {
            throw new Exception("Unauthorized");
        }
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getClient(): Client
    {
        return $this->client;
    }

    public function build(): MiddlewareUser
    {
        return $this;
    }

}
