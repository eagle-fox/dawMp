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
 * Esta clase se encarga de gestionar la autenticación y autorización de los usuarios..
 */
class MiddlewareUser
{
    /**
     * @var UUID Token de autenticación
     */
    public UUID $bearerToken;

    /**
     * @var IPv4 Dirección IP del cliente
     */
    public IPv4 $ipv4;

    /**
     * @var User Usuario autenticado, puede ser NULL si no se ha autenticado correctamente
     */
    public User $user;
    /**
     * @var Client Cliente autenticado, puede ser NULL si no se ha autenticado correctamente
     */
    public Client $client;
    /**
     * @var Email Email del usuario
     */
    private Email $email;
    /**
     * @var Password Contraseña del usuario, depende de la variable de entorno LEAF_DEV_TOOLS
     */
    private Password $password;
    /**
     * @var Rol OPCIONAL Rol al que se quiere acceder (ADMIN, USER, IOT, GUEST) pero es opcional por defecto es ADMIN.
     */
    private Rol $targetRol;

    /**
     * @var AuthMethods Método de autenticación, puede ser BEARER o BASIC
     */
    private AuthMethods $authMethod;

    /**
     * @var bool Variable de entorno LEAF_DEV_TOOLS
     */
    private bool $debug;

    /**
     * @var string Cabeceras de la petición
     */
    private string $headers;

    /**
     * @var Log Log de la petición
     */
    private Log $log;

    /**
     * @var mixed Id del usuario al que se quiere acceder, pero es opcional.
     */
    private mixed $targetId;

    /**
     * Constructor de la clase MiddlewareUser, se encarga de gestionar la autenticación y autorización de los usuarios.
     * @param Rol $targetRol Rol al que se quiere acceder (ADMIN, USER, IOT, GUEST) pero es opcional por defecto es ADMIN.
     * @param int|null $targetId Id del usuario al que se quiere acceder, pero es opcional.
     * @throws Exception en casos de problemas SQL
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

    /**
     * Lee las cabeceras de la petición y las guarda en la variable $headers
     * @throws InvalidArgumentException si no se encuentra la cabecera de autenticación o si no es soportada, actualmente solo soporta Bearer y Basic.
     */
    private function setHeaders(): void
    {
        $buffer = app()->request()->headers("Authorization");
        if ($buffer) {
            $this->headers = $buffer;
            return;
        }
        throw new InvalidArgumentException("No Authorization or unsupported header found");
    }

    /**
     * Establece el método de autenticación, puede ser BEARER o BASIC
     * @throws InvalidArgumentException si no es soportado, actualmente solo soporta Bearer y Basic.
     */
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

    /**
     * Establece la dirección IP del cliente, se obtiene de la petición.
     */
    private function setCurrentIp(): void
    {
        $this->ipv4 = new IPv4(app()->request()->getIp());
    }

    /**
     * Establece el usuario autenticado, dependiendo del método de autenticación.
     * @throws Exception si no se encuentra el usuario o si el usuario está bloqueado.
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
                        throw new Exception('User not found, data provided: ' . $this->bearerToken);
                    }
                } else {
                    $client = Client::query()->where("token", $this->bearerToken)->first();
                    if (!$client instanceof Client) {
                        throw new Exception('Client not found data provided: ' . $this->bearerToken);
                    }
                    $user = User::query()->where("id", $client->user)->first();
                    if (!$user instanceof User) {
                        throw new Exception('User not found data provided: ' . $this->bearerToken);
                    }
                    if ($user->locked) {
                        throw new Exception('User is locked' . $this->bearerToken);
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

    /**
     * Lee el token de autenticación Bearer, y lo asigna al tipo para verificar si es un dispositivo IOT o un cliente.
     * Si el token no es válido, se lanza una excepción desde el tipo UUID.
     */
    private function readBearerToken(): void
    {

        $parts = explode(" ", $this->headers);
        $this->bearerToken = new UUID($parts[1]);
    }

    /**
     * Actualiza el token de autenticación por IP, si el cliente ya tiene un token, se le asigna, si no, se le crea uno nuevo.
     */
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

    /**
     * Establece el cliente autenticado, si no existe, se crea uno nuevo.
     * @throws Exception si no se encuentra el cliente o si el cliente no se puede crear.
     */
    private function setClient(): void
    {
        $client = Client::query()->where("user", $this->user->id)->where("ipv4", $this->ipv4)->where("token", $this->bearerToken)->first();

        if ($client instanceof Client) {
            $this->client = $client;
            return;
        }

        $this->registerClient();
    }

    /**
     * Registra un nuevo cliente, si no existe.
     */
    private function registerClient(): void
    {
        $this->client = new Client();
        $this->client->user = $this->user->id;
        $this->client->ipv4 = new IPv4(app()->request()->getIp());
        $this->client->token = $this->bearerToken;
        $this->client->save();
    }

    /**
     * Comprueba si el usuario tiene permisos para acceder a la ruta, dependiendo del rol suministrado y requerido, así
     * mismo si está presente el id del usuario al que se quiere acceder si corresponde.
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
