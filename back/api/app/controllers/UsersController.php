<?php

namespace app\controllers;

use App\Models\User;
use Exception;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): void
    {
        if (!Utils::autenticate("ADMIN")) {
            response()->json(["message" => "No tienes permisos para ver los usuarios"], 401,);
        }
        try {
            response()->json(User::query()->get());
        } catch (Exception $e) {
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
        if (!Utils::autenticate("ADMIN")) {
            response()->json(["message" => "No tienes permisos para crear un usuario"], 401,);
        }
        try {
            $newUser = new User();
            $requestData = request()->try($newUser->getFillable());
            foreach ($requestData as $field => $value) {
                $newUser->$field = $value;
            }
            $newUser->token = Utils::generateUUID();
            $newUser->save();
            response()->json($newUser);
        } catch (Exception $e) {
            if (getenv("leaftools_dev")) {
                response()->json(["message" => "Error al crear el usuario: " . $e->getMessage(),], 500);
            }
            response()->json(["message" => "Error al crear el usuario"], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id): void
    {
        $requestedUser = User::query()->find($id);
        $authUser = Utils::getUserFromAutentication();

        if ($requestedUser instanceof User && $authUser instanceof User && $requestedUser->id ==
            $authUser->id) {
            response()->json($requestedUser);
        }

        if (!Utils::autenticate("ADMIN")) {
            response()->json(["message" => "No tienes permisos para ver este usuario"], 401,);
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
     */
    public function update($id): void
    {
        try {
            $user = Utils::autenticate();
            if ($user && ($user->rol == "ADMIN" || $user->id == $id)) {
                $userToUpdate = User::query()->find($id);
                if ($userToUpdate) {
                    $fields = $userToUpdate->getFillable();
                    $requestData = request()->try($fields);

                    $missingFields = array_diff($fields, array_keys($requestData),);

                    if ($_SERVER["REQUEST_METHOD"] === "PUT" && !empty($missingFields)) {
                        response()->json([
                                "message" => "Faltan campos requeridos para la actualización completa: " . implode(", ", $missingFields),
                            ], 400,);
                        return;
                    }

                    foreach ($requestData as $field => $value) {
                        $userToUpdate->$field = $value;
                    }

                    $userToUpdate->save();
                    response()->json($userToUpdate);
                } else {
                    response()->json(["message" => "Usuario no encontrado"], 404,);
                }
            } else {
                response()->json([
                        "message" => "No tienes permisos para actualizar este usuario",
                    ], 401,);
            }
        } catch (Exception $e) {
            $message = "Error al actualizar el usuario";
            if (getenv("leaftools_dev")) {
                $message .= ": " . $e->getMessage();
            }
            response()->json(["message" => $message], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $user = Utils::autenticate();
            if ($user && $user->rol == "ADMIN") {
                $user = User::query()->find($id);
                if ($user) {
                    $user->delete();
                    response()->json(["message" => "Usuario eliminado"]);
                } else {
                    response()->json(["message" => "Usuario no encontrado"], 404,);
                }
            } else {
                response()->json([
                        "message" => "No tienes permisos para eliminar este usuario",
                    ], 401,);
            }
        } catch (Exception $e) {
            $message = "Error al eliminar el usuario";
            if (getenv("leaftools_dev")) {
                $message .= ": " . $e->getMessage();
            }
            response()->json(["message" => $message], 500);
        }
    }
}
