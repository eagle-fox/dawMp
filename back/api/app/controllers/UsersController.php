<?php

namespace app\controllers;

use app\models\Client;
use App\Models\Log;
use App\Models\User;
use Exception;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): void
    {
        if (Utils::autenticate("ADMIN")) {
            $this->logAction("Unauthorized attempt to view all users");
            response()->json(["message" => "No tienes permisos para ver los usuarios"], 401,);
        }
        try {
            response()->json(User::query()->get());
            $this->logAction("Viewed all users");
        } catch (Exception $e) {
            $this->logAction("Error viewing all users: " . $e->getMessage());
            if (getenv("leaftools_dev")) {
                response()->json(["message" => "Error al mostrar los usuarios: " . $e->getMessage()], 500,);
            }
            response()->json(["message" => "Error al mostrar los usuarios"], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(): void
    {
        if (Utils::autenticate("ADMIN") || !Utils::isIpLocked() ) {
            $this->logAction("Unauthorized attempt to create a user");
            response()->json(["message" => "No tienes permisos para crear un usuario"], 401,);
        }
        try {
            $newUser = new User();
            $this->fillUser($newUser);

            $newClient = new Client();
            $newClient->ipv4 = request()->getIp();
            $newClient->token = Utils::generateUUID();
            $newClient->client = $newUser->id;
            $newClient->save();

            response()->json($newUser);
            $this->logAction("Created a new user");
        } catch (Exception $e) {
            $this->logAction("Error creating a new user: " . $e->getMessage());
            $message = "Error al crear el usuario";
            if (getenv("leaftools_dev")) {
                $message .= ": " . $e->getMessage();
            }
            response()->json(["message" => $message], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id): void
    {
        if (!Utils::autenticate("ADMIN")) {
            $this->logAction("Unauthorized attempt to view user with id: " . $id);
            response()->json(["message" => "No tienes permisos para ver este usuario"], 401,);
        }
        try {
            $user = User::query()->find($id);
            if ($user) {
                response()->json($user);
                $this->logAction("Viewed user with id: " . $id);
            } else {
                response()->json(["message" => "Usuario no encontrado"], 404,);
                $this->logAction("Failed to view user with id: " . $id . " - User not found");
            }
        } catch (Exception $e) {
            $this->logAction("Error viewing user with id: " . $id . " - " . $e->getMessage());
            if (getenv("leaftools_dev")) {
                response()->json(["message" => "Error al mostrar el usuario: " . $e->getMessage()], 500,);
            }
            response()->json(["message" => "Error al mostrar el usuario"], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id): void
    {
        if (!Utils::autenticate("ADMIN")) {
            $this->logAction("Unauthorized attempt to update user with id: " . $id);
            response()->json(["message" => "No tienes permisos para actualizar este usuario"], 401,);
        }
        try {
            $user = User::query()->find($id);
            if ($user instanceof User) {
                $this->fillUser($user);

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
     */
    public function destroy($id): void
    {
        if (!Utils::autenticate("ADMIN")) {
            $this->logAction("Unauthorized attempt to delete user with id: " . $id);
            response()->json(["message" => "No tienes permisos para eliminar este usuario"], 401,);
        }
        try {
            $user = User::query()->find($id);
            if ($user) {
                $user->delete();
                response()->json(["message" => "Usuario eliminado"]);
                $this->logAction("Deleted user with id: " . $id);
            } else {
                response()->json(["message" => "Usuario no encontrado"], 404,);
                $this->logAction("Failed to delete user with id: " . $id . " - User not found");
            }
        } catch (Exception $e) {
            $this->logAction("Error deleting user with id: " . $id . " - " . $e->getMessage());
            if (getenv("leaftools_dev")) {
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
        $log = new Log();
        $log->user = Utils::getUserFromAutentication()->id;
        $log->client = Client::query()->where('token', Utils::getUserFromAutentication()->token)->first()->id;
        $log->message = $message;
        $log->save();
    }

    /**
     * @param User|array $user
     * @return void
     */
    private function fillUser(User|array $user): void
    {
        $user->nombre = request()->input("nombre");
        $user->nombre_segundo = request()->input("nombre_segundo");
        $user->apellido_primero = request()->input("apellido_primero");
        $user->apellido_segundo = request()->input("apellido_segundo");
        $user->email = request()->input("email");
        $user->password = request()->input(hash("sha256", "password"));  // DON'T CHANGE THE ALGORITHM
        $user->rol = request()->input("rol");
        $user->locked = false;
        $user->save();
    }
}
