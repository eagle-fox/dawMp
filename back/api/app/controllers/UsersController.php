<?php

namespace app\controllers;

use app\models\Client;
use App\Models\Log;
use App\Models\User;
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
            $this->logAction("Unauthorized attempt to view all users");
            response()->json(["message" => "No tienes permisos para ver los usuarios"], 401,);
            return;
        }

        try {
            $users = User::query()->get();
            foreach ($users as $user) {
                $user->clients = Client::query()->where('client', $user->id)->get();
            }
            response()->json($users);
            $this->logAction("Viewed all users");
        } catch (Exception $e) {
            $this->logAction("Error viewing all users: " . $e->getMessage());
            if (getenv("LEAF_DEV_TOOLS")) {
                response()->json(["message" => "Error al mostrar los usuarios: " . $e->getMessage()], 500,);
            }
            response()->json(["message" => "Error al mostrar los usuarios"], 500);
        }
    }

    /**
     * Para crear un usuario, se necesita ser administrador o no estar bloqueado por IP.
     * @return void
     * @throws RandomException
     */
    public function store(): void
    {

        /**
        if (!Utils::autenticate("ADMIN")) {
            $this->logAction("Unauthorized attempt to create a new user");
            response()->json(["message" => "No tienes permisos para crear un usuario"], 401,);
            return;
        }*/

        try {
            $newUser = new User();
            $newUser->nombre = request()->get("nombre");
            $newUser->nombre_segundo = request()->get("nombre_segundo");
            $newUser->apellido_primero = request()->get("apellido_primero");
            $newUser->apellido_segundo = request()->get("apellido_segundo");
            $newUser->email = request()->get("email");
            $newUser->password = hash("sha256", request()->get("password"));
            $newUser->rol = request()->get("rol");
            $newUser->save();

            $newClient = new Client();
            $newClient->ipv4 = request()->getIp();
            $newClient->token = Utils::generateUUID();
            $newClient->client = $newUser->id;
            $newClient->save();

            $newUser->clients = Client::query()->where('client', $newUser->id)->get();
            response()->json($newUser);
            $this->logAction("Created a new user");

        } catch (Exception $e) {
            $this->logAction("Error creating a new user: " . $e->getMessage());
            $message = "Error al crear el usuario";
            /* Esta variable se manda por el docker-compose, en producción no vamos a darle muchos detalles al
            usuario obviamente. */
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
            if (getenv("leaftools_dev")) {
                response()->json(["message" => "Error al mostrar el usuario: " . $e->getMessage()], 500,);
            }
            response()->json(["message" => "Error al mostrar el usuario"], 500);
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
                $user->nombre = request()->get("nombre");
                $user->nombre_segundo = request()->get("nombre_segundo");
                $user->apellido_primero = request()->get("apellido_primero");
                $user->apellido_segundo = request()->get("apellido_segundo");
                $user->email = request()->get("email");
                $user->password = hash("sha256", request()->get("password"));
                $user->rol = request()->get("rol");
                $user->save();
                response()->json($user);
                $this->logAction("Updated user with id: " . $id);
            } else {
                response()->json(["message" => "Usuario no encontrado"], 404,);
                $this->logAction("Failed to update user with id: " . $id . " - User not found");
            }
        } catch (Exception $e) {
            $this->logAction("Error updating user with id: " . $id . " - " . $e->getMessage());
            if (getenv("leaftools_dev")) {
                response()->json(["message" => "Error al actualizar el usuario: " . $e->getMessage()], 500,);
            }
            response()->json(["message" => "Error al actualizar el usuario"], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @throws RandomException
     */
    public function destroy($id): void
    {
        if (!Utils::autenticate("ADMIN") || !Utils::getUserFromAutentication()->id == $id) {
            response()->json(["message" => "No tienes permisos para borrar este usuario"], 401);
            return;
        }
        try {
            $user = User::query()->find($id);
            if ($user) {
                $user->delete();
                Client::query()->where('client', $id)->delete();
                response()->json(["message" => "Usuario y clientes asociados eliminados correctamente"]);
                $this->logAction("Deleted user with id: " . $id);
            } else {
                response()->json(["message" => "Usuario no encontrado"], 404,);
                $this->logAction("Failed to delete user with id: " . $id . " - User not found");
            }
        } catch (Exception $e) {
            $this->logAction("Error deleting user with id: " . $id . " - " . $e->getMessage());
            if (getenv("LEAF_DEV_TOOLS")) {
                response()->json(["message" => "Error al eliminar el usuario: " . $e->getMessage()], 500,);
            }
            response()->json(["message" => "Error al eliminar el usuario"], 500);
        }
    }

    /**
     * Log an action in the log table.
     */
    private function logAction(string $message): void
{
    }

}
