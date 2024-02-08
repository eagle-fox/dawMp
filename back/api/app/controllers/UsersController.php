<?php

namespace App\Controllers;

use App\Models\User;
use Exception;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $user = Utils::authenticateByToken();
            if ($user && $user->rol == 'ADMIN') {
                $users = User::all();
                response()->json($users);
            } else {
                response()->json(['message' => 'No tienes permisos para ver los usuarios'], 401);
            }
        } catch (Exception $e) {
            $message = 'Error al mostrar los usuarios';
            if (getenv('leaftools_dev')) {
                $message .= ': ' . $e->getMessage();
            }
            response()->json(['message' => $message], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(): void
    {
        try {
            $user = Utils::authenticateByToken();
            if ($user && $user->rol == 'ADMIN') {
                $newUser = new User;
                $fields = $newUser->getFillable();
                $requestData = request()->try($fields);

                foreach ($requestData as $field => $value) {
                    $newUser->$field = $value;
                }

                $newUser->token = Utils::generateToken();
                $newUser->save();
                response()->json($newUser);

            } else {
                response()->json(['message' => 'No tienes permisos para crear un usuario'], 401);
            }
        } catch (Exception $e) {
            $this->response->json(['message' => 'Error al crear el usuario: motivo: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $user = Utils::authenticateByToken();
            if ($user && ($user->rol == 'ADMIN' || $user->id == $id)) {
                $user = User::query()->find($id);
                if ($user) {
                    response()->json($user);
                } else {
                    response()->json(['message' => 'Usuario no encontrado'], 404);
                }
            } else {
                response()->json(['message' => 'No tienes permisos para ver este usuario'], 401);
            }
        } catch (Exception $e) {
            $message = 'Error al mostrar el usuario';
            if (getenv('leaftools_dev')) {
                $message .= ': ' . $e->getMessage();
            }
            response()->json(['message' => $message], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id)
    {
        try {
            $user = Utils::authenticateByToken();
            if ($user && ($user->rol == 'ADMIN' || $user->id == $id)) {
                $userToUpdate = User::query()->find($id);
                if ($userToUpdate) {
                    $fields = $userToUpdate->getFillable();
                    $requestData = request()->try($fields);

                    $missingFields = array_diff($fields, array_keys($requestData));

                    if ($_SERVER['REQUEST_METHOD'] === 'PUT' && !empty($missingFields)) {
                        response()->json(['message' => 'Faltan campos requeridos para la actualización completa: ' . implode(', ', $missingFields)], 400);
                        return;
                    }

                    foreach ($requestData as $field => $value) {
                        $userToUpdate->$field = $value;
                    }

                    $userToUpdate->save();
                    response()->json($userToUpdate);
                } else {
                    response()->json(['message' => 'Usuario no encontrado'], 404);
                }
            } else {
                response()->json(['message' => 'No tienes permisos para actualizar este usuario'], 401);
            }
        } catch (Exception $e) {
            $message = 'Error al actualizar el usuario';
            if (getenv('leaftools_dev')) {
                $message .= ': ' . $e->getMessage();
            }
            response()->json(['message' => $message], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $user = Utils::authenticateByToken();
            if ($user && $user->rol == 'ADMIN') {
                $user = User::query()->find($id);
                if ($user) {
                    $user->delete();
                    response()->json(['message' => 'Usuario eliminado']);
                } else {
                    response()->json(['message' => 'Usuario no encontrado'], 404);
                }
            } else {
                response()->json(['message' => 'No tienes permisos para eliminar este usuario'], 401);
            }
        } catch (Exception $e) {
            $message = 'Error al eliminar el usuario';
            if (getenv('leaftools_dev')) {
                $message .= ': ' . $e->getMessage();
            }
            response()->json(['message' => $message], 500);
        }
    }

}
