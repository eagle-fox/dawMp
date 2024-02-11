<?php

namespace app\controllers;

use app\models\Client;
use App\Models\User;
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
        // Generate 16 bytes (128 bits) of random data or use the data passed into the function.
        $data = $data ?? random_bytes(16);
        assert(strlen($data) == 16);
        // Set version to 0100
        $data[6] = chr((ord($data[6]) & 0x0f) | 0x40);
        // Set bits 6-7 to 10
        $data[8] = chr((ord($data[8]) & 0x3f) | 0x80);
        // Output the 36 character UUID.
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

        $token = "";
        if ($parts[0] == "Bearer") {
            $token = $parts[1];
            $user = self::getUserFromToken($token);
        } elseif ($parts[0] == "Basic") {
            $user = self::getUserFromBasic($parts[1]);
        } else {
            response()->json(["message" => "Invalid authentication method"], 401);
            return False;
        }

        if (!$user) {
            // UNAUTHORIZED
            return False;
        }

        /**
         * Obtenemos una lista de los tokenes activo, si su IP ha variado lo actualizamos,
         * si no existe, lo creamos.
         */
        $clients = self::getClientsFromUser($user);
        if (count($clients) > 0) {
            foreach ($clients as $client) {
                if ($client->token == $token) {
                    $client->ipv4 = request()->getIp();
                    $client->save();
                    return $user;
                }
                if ($client->ipv4 == request()->getIp()) {
                    $client->token = $token;
                    $client->save();
                    return $user;
                }
            }
        }

        self::registerClient($user);

        return False;
    }

    public static function getClientsFromUser($user)
    {
        return Client::query()->where("client", $user->id)->get();
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

}
