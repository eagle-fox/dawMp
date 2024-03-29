<?php

namespace app\controllers;

use app\middlewares\MiddlewareUser;
use app\models\Client;
use app\models\IotData;
use app\models\IotDevice;
use app\models\Log;
use app\models\User;
use app\types\Rol;
use app\types\UUID;
use Exception;
use Leaf\Http\Request;
use Random\RandomException;

class UsersController extends Controller
{

    /**
     * Obtenemos una lista de todos los usuarios, incluyendo sus clientes.
     * Solamente los administradores pueden ver todos los usuarios.
     */
    public function index(): void
    {
        try {
            $start = microtime(true);
            set_time_limit(300);
            ini_set('memory_limit', '1024M');

            $auth = new MiddlewareUser(Rol::ADMIN);
            $users = User::query()->get();

            $totalUsers = User::query()->count();
            $totalClients = Client::query()->count();
            $totalIotDevices = IotDevice::query()->count();
            $totalIotData = IotData::query()->count();

            $memoriaUsadaMb = memory_get_usage() / 1024 / 1024;
            $memoriaMaximaMb = memory_get_peak_usage() / 1024 / 1024;
            $memoriaUsada = round($memoriaUsadaMb, 2) . " MB";
            $memoriaMaxima = round($memoriaMaximaMb, 2) . " MB";

            $end = microtime(true);

            response()->json([
                "totalUsers"    => $totalUsers,
                "totalClients"  => $totalClients,
                "totalIotDevices" => $totalIotDevices,
                "totalIotData"  => $totalIotData,
                "memoriaUsada"  => $memoriaUsada,
                "memoriaMaxima" => $memoriaMaxima,
                "tiempo"        => round($end - $start, 2) . " segundos",
                "users"         => $users
            ], 200);

        } catch (Exception $e) {
            $msg = "Error al mostrar los usuarios";
            if (getenv("LEAF_DEV_TOOLS")) {
                $msg .= ": " . $e->getMessage();
            }
            error_log($msg);
            response()->json(["message" => $msg], 500);
        }
    }

    /**
     * Para crear un usuario, se necesita ser administrador o no estar bloqueado por IP.
     * @return void
     */
    public function store(): void
    {

        try {
            $auth = new MiddlewareUser(Rol::GUEST);
            $newUser = new User();
            $this->fillUserData($newUser);

            $client = new Client();
            $client->user = $newUser->id;
            $client->ipv4 = $auth->ipv4;
            $client->token = new UUID();
            $client->save();

            response()->json(["user" => $newUser, "client" => $client]);

        } catch (Exception $e) {
            /* Esta variable se manda por el docker-compose, en producción no vamos a darle muchos detalles al
            usuario obviamente. */
            $message = "Error al crear el usuario";
            if (getenv("LEAF_DEV_TOOLS")) {
                $message .= ": " . $e->getMessage();
            }
            response()->json(["message" => $message], 500);
        }
    }

    /**
     * Log an action in the log table.
     */
    private function logAction(User $user, Client $client): void
    {
        $log = new Log();
        $log->user = $user->id;
        $log->client = $client->id;
        $log->message = "Unauthorized attempt to create a new user";
        $log->save();
    }

    /**
     * @param user $user
     * @param bool $requireAllFields
     * @return void
     * @throws Exception
     */
    private function fillUserData(user $user, bool $requireAllFields = true): void
    {
        $fields = $user->getFillableFields();
        foreach ($fields as $field) {
            if (request()->get($field) !== null) {
                if ($field === 'password') {
                    // Check if the password is already hashed
                    if (strlen(request()->get($field)) === 64) {
                        $user->$field = request()->get($field);
                    } else {
                        $user->$field = hash("sha256", request()->get($field));
                    }
                } else {
                    $user->$field = request()->get($field);
                }
            } elseif ($requireAllFields) {
                throw new Exception("Missing field: $field");
            }
        }
        $user->save();
    }

    /**
     * Aquí mostramos un usuario en específico, el administrador puede consultar cualquier usuario pero el usuario
     * solamente puede ver su información para recuperar por ejemplo, el TOKEN de sesión o ver donde ha iniciado.
     * @param $id
     * @return void
     * @throws RandomException|Exception
     */
    public function show($id = null): void
    {
        try {
            $auth = new MiddlewareUser(Rol::USER, $id);
            $user = User::query()->find($id);
            if ($user instanceof User) {
                response()->json($user);
            } else {
                response()->json(["message" => "Usuario no encontrado"], 404);
            }
        } catch (Exception $e) {
            $message = "Error al mostrar el usuario";
            if (getenv("LEAF_DEV_TOOLS")) {
                $message .= ": " . $e->getMessage();
            }
            response()->json(["message" => $message], 500);
        }
    }

    public function login(): void
    {
        try {
            $auth = new MiddlewareUser(Rol::USER);
            response()->json(["message" => "Bienvenido", "user" => $auth->user]);
        } catch (Exception $e) {
            $message = "Error al iniciar sesión";
            if (getenv("LEAF_DEV_TOOLS")) {
                $message .= ": " . $e->getMessage();
            }
            response()->json(["message" => $message], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     * @throws Exception
     */
    public function update($id): void
    {

        try {
            $auth = new MiddlewareUser(Rol::USER, $id);
            $user = User::query()->find($id);
            if (Request::getMethod() === "PUT" && $user instanceof User) {
                // For PUT requests, we update all fields
                $this->fillUserData($user);
            } else if (Request::getMethod() === "PATCH" && $user instanceof User) {
                // For PATCH requests, we update only the fields that are present in the request
                $this->fillUserData($user, false);
                response()->json($user);
            } else {
                response()->json(["message" => "Usuario no encontrado"], 404);
            }
        } catch (Exception $e) {
            $message = "Error al actualizar el usuario";
            if (getenv("LEAF_DEV_TOOLS")) {
                $message .= ": " . $e->getMessage();
            }
            response()->json(["message" => $message], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @throws Exception
     */
    public function destroy($id): void
    {
        try {
            set_time_limit(300);
            ini_set('memory_limit', '1024M');

            $auth = new MiddlewareUser(Rol::USER, $id);
            $user = User::query()->where("id", $id)->first();
            if (!$user) {
                response()->json(["message" => "Usuario no encontrado"], 404);
                return;
            }

            $iotDevices = IotDevice::query()->where("user", $id)->get();
            foreach ($iotDevices as $device) {
                $iotData = IotData::query()->where("device", $device->id)->get();
                foreach ($iotData as $data) {
                    $data->delete();
                }
                $device->delete();
            }

            $logs = Log::query()->where("user", $id)->get();
            foreach ($logs as $log) {
                $log->delete();
            }

            $clients = Client::query()->where("user", $id)->get();
            foreach ($clients as $client) {
                $client->delete();
            }

            $user->delete();
            response()->json(["message" => "Usuario eliminado"]);
        } catch (Exception $e) {
            $message = "Error al eliminar el usuario";
            if (getenv("LEAF_DEV_TOOLS")) {
                $message .= ": " . $e->getMessage();
            }
            response()->json(["message" => $message], 500);
        }
    }
}
