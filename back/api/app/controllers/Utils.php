<?php

namespace app\controllers;

use app\models\Client;
use App\Models\User;
use Random\RandomException;

class Utils {

    /**
     * For simplicity, we use a 32 character UUID as a token.
     * @param $length int our DB is RFC 4122 compliant so we use 32
     * @return string
     * @throws RandomException
     */
    public static function generateUUID($length = 32): string {
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
     * Verify if the token is valid and the user has the required rol to access the endpoint
     * Bearer Token is a token that is sent in the header of the request (the UUID)
     * @param $token string
     * @param $rol string
     * @return bool
     */
    public static function authenticateWithBearerToken($token, $rol): bool {
        $user = User::query()
            ->where("token", $token)
            ->first();
        return $user instanceof User && $user->rol == $rol;
    }

    /**
     * Verify if the credentials are valid and the user has the required rol to access the endpoint
     * Basic Auth is a base64 encoded string with the email and password separated by a colon
     * @param $credentials string
     * @param $rol string
     * @return bool
     */
    public static function authenticateWithBasicAuth($credentials, $rol): bool {
        [$email, $password] = explode(":", base64_decode($credentials));
        $user = User::query()
            ->where("email", $email)
            ->first();
        return $user instanceof User && hash("sha256", $password) == $user->password && $user->rol == $rol;
    }

    /**
     * Verify if the user has the required rol to access the endpoint
     * By default, the rol is ADMIN during development stages
     * @param string $rol
     * @return bool
     */
    public static function autenticate(string $rol = "ADMIN"): bool {
        $headers = request()->headers("Authorization");
        if ($headers) {
            $parts = explode(" ", $headers);
            if (count($parts) == 2) {
                if ($parts[0] == "Bearer") {
                    return self::authenticateWithBearerToken($parts[1], $rol);
                } elseif ($parts[0] == "Basic") {
                    return self::authenticateWithBasicAuth($parts[1], $rol);
                }
            }
        }
        return false;
    }

    /**
     * Get the user from the token
     * @param $token string
     * @return User|False
     */
    public static function getUserFromToken(string $token): User | False {
        $user = User::query()
            ->where("token", $token)
            ->first();
        return $user instanceof User ? $user : False;
    }

    /**
     * Get the user from the basic auth
     * @param $credentials string
     * @return User|False
     */
    public static function getUserFromBasic(string $credentials): User | False {
        [$email, $password] = explode(":", base64_decode($credentials));
        $user = User::query()
            ->where("email", $email)
            ->first();
        return $user instanceof User ? $user : False;
    }

    /**
     * Get the user from the authentication header, dosen't matter if it's Bearer or Basic is handled outside.
     * @return User|False
     */
    public static function getUserFromAutentication(): User | False {
        $headers = request()->headers("Authorization");
        if ($headers) {
            $parts = explode(" ", $headers);
            if (count($parts) == 2) {
                if ($parts[0] == "Bearer") {
                    return self::getUserFromToken($parts[1]);
                } elseif ($parts[0] == "Basic") {
                    return self::getUserFromBasic($parts[1]);
                }
            }
        }
        return False;
    }

    /**
     * Verify if the IP is locked, IPs are stored as unsigned integers in the DB!
     * @return bool
     */
    public static function isIpLocked(): bool
    {
        $ip = request()->getIp();
        $client = Client::query()->where('ipv4', ip2long($ip))->first();

        if ($client instanceof Client) {
            return $client->locked;
        }

        return false;
    }

    /**
     * Register the client in the DB, authenticated or not!
     * Here the FK is the user id, if the user is not authenticated, the client is registered could be NULL
     * @throws RandomException
     */
    public static function registerClient(): void
    {
        $newClient = new Client();
        $newClient->ipv4 = ip2long(request()->getIp());
        $newClient->token = Utils::generateUUID();

        $user = Utils::getUserFromAutentication();

        if ($user instanceof User) {
            $newClient->client = $user->id;
        } else {
            $newClient->client = null;
        }

        $newClient->save();
    }
}
