<?php

namespace app\controllers;

use app\models\Client;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Random\RandomException;

class Utils
{
    /**
     * For simplicity, we use a 32 character UUID as a token.
     * @param $length int our DB is RFC 4122 compliant so we use 32
     * @return string
     * @throws RandomException
     */
    public static function generateUUID($length = 32): string
    {
        $data = $data ?? random_bytes(16);
        assert(strlen($data) == 16);
        $data[6] = chr((ord($data[6]) & 0x0f) | 0x40);
        $data[8] = chr((ord($data[8]) & 0x3f) | 0x80);
        return vsprintf("%s%s-%s-%s-%s-%s%s%s", str_split(bin2hex($data), 4));
    }

    /**
     * Get the user from the token
     * @param $token string
     * @return User|False
     */
    public static function getUserFromToken(string $token): User|false
    {
        $user = User::query()->where("token", $token)->first();
        return $user instanceof User ? $user : False;
    }

    /**
     * Get the user from the basic auth
     * @param $credentials string
     * @return User|False
     */
    public static function getUserFromBasic(string $credentials): User|false
    {
        [$email, $password] = explode(":", base64_decode($credentials));
        $user = User::query()->where("email", $email)->first();
        return $user instanceof User ? $user : False;
    }

    /**
     * @throws RandomException
     */
    public static function autenticate($rol = "ADMIN"): bool
    {
        $user = self::getUserFromAutentication();
        if ($user instanceof User) {
            return $user->rol == $rol;
        }
        return False;
    }

    /**
     * Get the user from the authentication header, dosen't matter if it's Bearer or Basic is handled outside.
     * @return User|False
     * @throws RandomException
     */
    public static function getUserFromAutentication(): User|false
    {
        $headers = request()->headers("Authorization");
        $parts = explode(" ", $headers);

        if ($parts[0] == "Bearer") {
            return self::authenticateWithBearer($parts[1]);
        } elseif ($parts[0] == "Basic") {
            return self::authenticateWithBasic($parts[1]);
        } else {
            self::handleAuthenticationError("Unsupported authentication method");
            return False;
        }
    }

    private static function authenticateWithBearer(string $token): User|false
    {
        $user = self::getUserFromToken($token);
        if (!$user) {
            self::handleAuthenticationError("Invalid credentials");
        }
        return $user;
    }

    private static function authenticateWithBasic(string $credentials): User|false
    {
        $user = self::getUserFromBasic($credentials);
        if (!$user) {
            self::handleAuthenticationError("Invalid credentials");
            return False;
        }

        $ip = request()->getIp();
        $clients = self::getClientsFromUser($user);
        if (count($clients) > 0) {
            foreach ($clients as $client) {
                if ($client->ipv4 == $ip) {
                    return $user;
                }
            }
        }

        self::registerClient($user);
        return $user;
    }

    private static function handleAuthenticationError(string $message): void
    {
        if (getenv("LEAF_DEV_TOOLS")) {
            response()->json(["message" => $message], 401);
        }
    }

    /**
     * Register the client in the DB, authenticated or not!
     * Here the FK is the user id, if the user is not authenticated, the client is registered could be NULL
     * @throws RandomException
     */
    public static function registerClient($user): void
    {
        $newClient = new Client();
        $newClient->ipv4 = request()->getIp();
        $newClient->token = self::generateUUID();
        $newClient->client = $user->id;
        $newClient->save();
    }

    public static function getClientsFromUser($user): Collection|array
    {
        return Client::query()->where("client", $user->id)->get();
    }

    /**
     * Get the connected client from the user
     * @param User $user
     * @return Client|False
     */
    public static function getConnectedClient(User $user): Client|false
    {
        $clients = self::getClientsFromUser($user);
        foreach ($clients as $client) {
            if ($client->ipv4 == request()->getIp() || $client->token == request()->headers("Authorization")) {
                return $client;
            }
        }
        return False;
    }
}
