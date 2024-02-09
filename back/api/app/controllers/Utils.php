<?php

namespace app\controllers;

use app\models\Client;
use App\Models\User;

class Utils {
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

    public static function autenticate($rol = "ADMIN"): bool {
        $headers = request()->headers("Authorization");
        if ($headers) {
            $parts = explode(" ", $headers);
            if (count($parts) == 2) {
                if ($parts[0] == "Bearer") {
                    // Token-based authentication
                    $token = $parts[1];
                    $user = User::query()
                        ->where("token", $token)
                        ->first();
                    if ($user instanceof User && $user->rol == $rol) {
                        return true;
                    }
                } elseif ($parts[0] == "Basic") {
                    // Basic authentication
                    [$email, $password] = explode(
                        ":",
                        base64_decode($parts[1]),
                    );
                    $user = User::query()
                        ->where("email", $email)
                        ->first();
                    if (
                        $user instanceof User &&
                        hash("sha256", $password) == $user->password &&
                        $user->rol == $rol
                    ) {
                        return true;
                    }
                }
            }
        }
        return false;
    }

    public static function getUserFromAutentication(): User | False {
        $headers = request()->headers("Authorization");
        if ($headers) {
            $parts = explode(" ", $headers);
            if (count($parts) == 2) {
                if ($parts[0] == "Bearer") {
                    // Token-based authentication
                    $token = $parts[1];
                    $user = User::query()
                        ->where("token", $token)
                        ->first();
                    if ($user instanceof User) {
                        return $user;
                    }
                } elseif ($parts[0] == "Basic") {
                    // Basic authentication
                    [$email, $password] = explode(
                        ":",
                        base64_decode($parts[1]),
                    );
                    $user = User::query()
                        ->where("email", $email)
                        ->first();
                    if ($user instanceof User) {
                        return $user;
                    }
                }
            }
        }
        return False;
    }

    public static function isIpLocked(): bool
    {
        $ip = request()->getIp();
        $client = Client::query()->where('ipv4', ip2long($ip))->first();

        if ($client instanceof Client) {
            return $client->locked;
        }

        return false;
    }


    public static function registerClient($user)
    {
        $newClient = new Client();
        $newClient->ipv4 = request()->getIp();
        $newClient->token = Utils::generateUUID();

        // check if the header contains auth basic or bearer, on that case search the client and save it, if not, is
        // null
        $user = Utils::getUserFromAutentication();
        if ($user instanceof User) {
            $newClient->client = $user->id;
        }

        $newClient->save();
    }

}
