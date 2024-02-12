<?php

namespace app\middlewares;

use app\controllers\Utils;
use app\models\Client;
use App\Models\User;
use app\types\UUID;
use app\types\IPv4;
use Leaf\Http\Request;

class AuthMiddleware extends \Leaf\Middleware
{
    public function call()
    {
        if (!request()->headers("Authorization")) {
            response()->json(["message" => "No tienes permisos para realizar esta acción"], 401);
            return;
        }

        $auth = explode(" ", request()->headers("Authorization"));

        if ($auth[0] === "Bearer") {
            $token = $auth[1];
            $client = Client::query()->where("token", $token)->first();

            if ($client !== null) {
                $user = User::query()->find($client->user);
                if ($user !== null) {
                    self::updateClient($user, new UUID($token));
                    $this->next();
                } else {
                    if (getenv("LEAF_DEV_TOOLS") === "true") {
                        response()->json(["message" => "Error retrieving user, probably a broken SQL relation"], 500);
                    }
                    exit();
                }
            } else {
                response()->json(["message" => "Invalid token"], 401);
                exit();
            }

        } else if ($auth[0] === "Basic") {
            $credentials = base64_decode($auth[1]);
            $credentials = explode(":", $credentials);
            $hash = hash("sha256", $credentials[1]);
            $user = User::query()->where("email", $credentials[0])->where("password", $hash)->first();
            if ($user instanceof User) {
                self::updateClient($user);
                $this->next();
            } else {
                response()->json(["message" => "Invalid credentials"], 401);
                exit();
            }
        } else {
            response()->json(["message" => "Unsupported authorization method"], 401);
            exit();
        }
    }

    private function updateClient(User $user, UUID $token = null ): void
    {
        if ($token === null) {
            $token = new UUID();
            $client = Client::query()->where("ipv4", ip2long(app()->request()->getIp()))->first();
            if ($client instanceof Client) {
                $client->ipv4 = new IPv4(app()->request()->getIp());
            } else {
                $client = new Client();
                $client->ipv4 = new IPv4(app()->request()->getIp());
                $client->token = $token;
                $client->user = $user->id;
            }
            $client->save();
        } else {
            $client = Client::query()->where("token", $token)->first();
            if ($client instanceof Client) {
                $client->ipv4 = new IPv4(app()->request()->getIp());
            } else {
                $client = new Client();
                $client->ipv4 = new IPv4(app()->request()->getIp());
                $client->token = $token;
                $client->user = $user->id;
            }
            $client->save();
        }
    }
}
