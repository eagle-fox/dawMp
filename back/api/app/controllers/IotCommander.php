<?php

namespace app\controllers;

use app\models\Client;
use App\Models\IotDevicesUser;
use app\models\Log;
use App\Models\User;
use Exception;
use Random\RandomException;

class IotCommander extends Controller
{

    /**
     * Obtendremos una lista de los dispositivos IoT existentes, y cuales están asociados a un usuario.
     * Por simplicidad, solo nos devolverá la última posición conocida de cada dispositivo.
     * @return void
     * @throws RandomException
     */
    public function index(): void
    {
        if (!Utils::autenticate("ADMIN")) {
            response()->json(["message" => "No tienes permisos para ver los dispostivos"], 401);
            return;
        }
        try {
            $currUser = Utils::getUserFromAutentication();
            $currClient = Utils::getConnectedClient($currUser);

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

    public function destroy($id): void
    {

    }

}
