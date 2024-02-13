<?php
declare(strict_types=1);

namespace app\middlewares;

use app\models\Client;
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

class MiddlewareBuilder
{
    private UUID $bearerToken;
    private IPv4 $ip;
    private User $user;
    private Email $email;
    private Password $password;
    private Client $client;
    private Rol $targetRol;
    private AuthMethods $authMethod;
    private bool $debug;
    private string $headers;
    private Log $log;

    /**
     * @throws Exception
     */
    public function __construct(Rol $targetRol = Rol::ADMIN)
    {
        $this->targetRol = $targetRol;
        $this->debug = getenv("LEAF_DEV_TOOLS") === "true";
        $this->getHeaders();
        $this->getAuthMethod();
        $this->getCurrentIp();
        $this->getUser();
        $this->getUserToken();
        $this->getClient();
        $this->checkRol();
    }

    private function getCurrentIp()
    {
        $this->ip = new IPv4(app()->request()->getIp());
    }

    /**
     * @throws Exception
     */
    private function getUser(): void
    {
        switch ($this->authMethod) {
            case AuthMethods::BEARER:
                $user = Client::query()->with('user')->where("token", $this->bearerToken)->first();
                if (!$user instanceof User) {
                    throw new Exception('User not found');
                }
                $this->user = $user->user;
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
                $this->log->message = "Unsupported authentication method";
                $this->log->save();
                throw new InvalidArgumentException("Unsupported authentication method");
        }
    }


    private function getHeaders(): void
    {
        $buffer = app()->request()->headers("Authorization");
        if ($buffer) {
            $this->headers = $buffer;
            return;
        }
        $this->log = new Log();
        $this->log->message = "No Authorization or unsupported header found";
        $this->log->save();
        throw new InvalidArgumentException("No Authorization or unsupported header found");

    }

    private function getAuthMethod(): void
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

    private function getUserToken(): void
    {
        $clients = Client::query()->where("user", $this->user->id);
        foreach ($clients as $client) {
            if ($client->ipv4 == $this->ip) {
                $this->bearerToken = $client->token;
                return;
            }
        }
        $this->bearerToken = new UUID();
    }

    private function getClient(): void
    {
        $found = false;
        $clients = Client::query()->where("user", $this->user->id)->get();
        foreach ($clients as $client) {
            if ($client->ipv4 == $this->ip) {
                $this->client = $client;
                $this->client->token = $this->bearerToken;
                $this->client->save();
                $found = true;
            }
        }
        if (!$found) {
            $this->registerClient();
        }
    }

    private function registerClient(): void
    {
        $this->client = new Client();
        $this->client->user = $this->user->id;
        $this->client->ipv4 = $this->ip;
        $this->client->token = $this->bearerToken;
        $this->client->save();
    }

    public function build(): MiddlewareBuilder
    {
        return $this;
    }

    /**
     * @throws Exception
     */
    private function checkRol(): void
    {
        if ($this->user->rol !== $this->targetRol) {
            if ($this->debug) {
                throw new Exception("Insufficient permissions: was required {$this->targetRol} but got {$this->user->rol}");
            }
            throw new Exception("Insufficient permissions");
        }
    }

}
