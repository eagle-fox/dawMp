<?php

namespace app\controllers;

use app\models\client;
use app\models\log;
use app\models\user;
use Exception;
use Random\RandomException;

class UsersController extends Controller
{
    /**
     * Obtenemos una lista de todos los usuarios, incluyendo sus clientes.
     * Solamente los administradores pueden ver todos los usuarios.
     * @throws RandomException
     */
    public function index(): void
    {
        if (!Utils::autenticate("ADMIN")) {
            response()->json(["message" => "No tienes permisos para ver los usuarios"], 401,);
            return;
        }

        try {
            $currUser = Utils::getUserFromAutentication();
            $currClient = Utils::getConnectedClient($currUser);
            $users = User::query()->get();
            $this->logAction($currUser, $currClient, "Viewed all users");
            response()->json($users);
        } catch (Exception $e) {
            if (getenv("LEAF_DEV_TOOLS")) {
                response()->json(["message" => "Error al mostrar los usuarios: " . $e->getMessage()], 500,);
            }
            response()->json(["message" => "Error al mostrar los usuarios"], 500);
        }
    }

    /**
     * Para crear un usuario, se necesita ser administrador o no estar bloqueado por IP.
     * @return void
     */
    public function store(): void
    {

        /**
         * if (!Utils::autenticate("ADMIN")) {
         * $this->logAction("Unauthorized attempt to create a new user");
         * response()->json(["message" => "No tienes permisos para crear un usuario"], 401,);
         * return;
         * }*/

        try {
            $currUser = Utils::getUserFromAutentication();
            $currClient = Utils::getConnectedClient($currUser);
            if ($currClient->locked===1) {
                $this->logAction($currUser, $currClient, "Unauthorized attempt to create a new user");
                response()->json(["message" => "No tienes permisos para crear un usuario"], 401,);
                return;
            }

            $newUser = new User();
            $this->fillUserData($newUser);

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
     * Aquí mostramos un usuario en específico, el administrador puede consultar cualquier usuario pero el usuario
     * solamente puede ver su información para recuperar por ejemplo, el TOKEN de sesión o ver donde ha iniciado.
     * @param $id
     * @return void
     * @throws RandomException
     */
    public function show($id): void
    {
        if (!Utils::autenticate("ADMIN") || !Utils::getUserFromAutentication()->id == $id) {
            response()->json(["message" => "No tienes permisos para ver este usuario"], 401);
            return;
        }
        try {
            $user = User::query()->find($id);
            if ($user) {
                response()->json($user);
            } else {
                response()->json(["message" => "Usuario no encontrado"], 404,);
            }
        } catch (Exception $e) {
            $message = "Error al mostrar el usuario";
            if (getenv("LEAF_DEV_TOOLS")) {
                $message .= ": " . $e->getMessage();
            }
            response()->json(["message" => $message], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     * @throws RandomException
     */
    public function update($id): void
    {
        if (!Utils::autenticate("ADMIN") || !Utils::getUserFromAutentication()->id == $id) {
            response()->json(["message" => "No tienes permisos para actualizar este usuario"], 401);
            return;
        }
        try {
            $user = User::query()->find($id);
            if ($user instanceof User) {
                if (\Leaf\Http\Request::getMethod() === "PUT") {
                    // For PUT requests, we update all fields
                    $this->fillUserData($user);
                } else if (\Leaf\Http\Request::getMethod() === "PATCH") {
                    // For PATCH requests, we update only the fields that are present in the request
                    $this->fillUserData($user, false);
                }
                response()->json($user);
            } else {
                response()->json(["message" => "Usuario no encontrado"], 404,);
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
     * @throws RandomException
     */
    public function destroy($id): void
    {
        if (!Utils::autenticate("ADMIN")) {
            response()->json(["message" => "No tienes permisos para borrar este usuario"], 401);
            return;
        }
        if (Utils::getUserFromAutentication()->id == $id) {
            response()->json(["message" => "No puedes borrar tu propio usuario"], 401);
            return;
        }

        try {
            $user = User::query()->find($id);
            $log = Log::query()->where("user", $id)->get();
            $clients = Client::query()->where("client", $id)->get();
            if ($user instanceof User) {
                foreach ($log as $l) {
                    $l->delete();
                }
                foreach ($clients as $c) {
                    $c->delete();
                }
                $user->delete();
                response()->json(["message" => "Usuario eliminado"]);
            } else {
                response()->json(["message" => "Usuario no encontrado"], 404,);
            }
        } catch (Exception $e) {
            $message = "Error al eliminar el usuario";
            if (getenv("LEAF_DEV_TOOLS")) {
                $message .= ": " . $e->getMessage();
            }
            response()->json(["message" => $message], 500);
        }
    }

    /**
     * Log an action in the log table.
     */
    private function logAction(User $user, Client $client, string $msg): void
    {
        $log = new Log();
        $log->user = $user->id;
        $log->client = $client->id;
        $log->message = $msg;
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
                    $user->$field = hash("sha256", request()->get($field));
                } else {
                    $user->$field = request()->get($field);
                }
            } elseif ($requireAllFields) {
                throw new Exception("Missing field: $field");
            }
        }
        $user->save();
    }
}
