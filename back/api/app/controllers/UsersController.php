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
        response()->json(User::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store()
    {
        try {
            $user = new User;
            $user->nombre = request()->get('nombre');
            $user->nombre_segundo = request()->get('nombre_segundo');
            $user->apellido_primero = request()->get('apellido_primero');
            $user->apellido_segundo = request()->get('apellido_segundo');
            $user->email = request()->get('email');
            $user->password = request()->get('password');
            $user->rol = request()->get('rol');

            $user->token = bin2hex(random_bytes(16)); // 16 bytes == 32 chars
            $user->locked = false;

            $user->save();
            $this->response->json($user);
        } catch (Exception $e) {
            $this->response->json(['message' => 'Error al crear el usuario: motivo: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        /**
        if ($this->request->get('token') == 'admin') {
            $user = User::find($id);
            if ($user) {
                response()->json($user);
            } else {
                response()->json(['message' => 'Usuario no encontrado'], 404);
            }
        } else {
            response()->json(['message' => 'No tienes permisos para ver este usuario'], 401);
        } */

        response()->json(User::find($id));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id)
    {
        try {
            $user = User::find($id);
            if ($user) {
                $user->nombre = request()->get('nombre');
                $user->nombre_segundo = request()->get('nombre_segundo');
                $user->apellido_primero = request()->get('apellido_primero');
                $user->apellido_segundo = request()->get('apellido_segundo');
                $user->email = request()->get('email');
                $user->password = request()->get('password');
                $user->rol = request()->get('rol');
                $user->locked = request()->get('locked');
                $user->save();
                response()->json($user);
            } else {
                response()->json(['message' => 'Usuario no encontrado'], 404);
            }
        } catch (Exception $e) {
            response()->json(['message' => 'Error al actualizar el usuario: motivo: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $user = User::find($id);
            if ($user) {
                $user->delete();
                response()->json(['message' => 'Usuario eliminado']);
            } else {
                response()->json(['message' => 'Usuario no encontrado'], 404);
            }
        } catch (Exception $e) {
            response()->json(['message' => 'Error al eliminar el usuario: motivo: ' . $e->getMessage()], 500);
        }
    }

    public function loginByEmailAndPassword()
    {
        try {
            $email = request()->get('email');
            $password = request()->get('password');
            $user = User::where('email', $email)->where('password', $password)->first();
            if ($user) {
                $user->token = bin2hex(random_bytes(16)); // Generate a new token
                $user->save();
                response()->json($user, 200);
            } else {
                response()->json(['message' => 'Usuario o contraseña incorrectos'], 401);
            }
        } catch (Exception $e) {
            $message = 'Error al iniciar sesión';
            if (getenv('leaftools_dev')) {
                $message .= ': ' . $e->getMessage();
            }
            response()->json(['message' => $message], 500);
        }
    }

    public function loginByTokenAndEmail()
    {
        try {
            $email = request()->get('email');
            $token = request()->get('token');
            $user = User::where('email', $email)->where('token', $token)->first();
            if ($user) {
                response()->json($user, 200);
            } else {
                response()->json(['message' => 'Token o email incorrectos'], 401);
            }
        } catch (Exception $e) {
            $message = 'Error al iniciar sesión';
            if (getenv('leaftools_dev')) {
                $message .= ': ' . $e->getMessage();
            }
            response()->json(['message' => $message], 500);
        }
    }

    public function loginByToken()
    {
        try {
            $token = request()->get('token');
            $user = User::where('token', $token)->first();
            if ($user) {
                response()->json($user, 200);
            } else {
                response()->json(['message' => 'Token incorrecto'], 401);
            }
        } catch (Exception $e) {
            $message = 'Error al iniciar sesión';
            if (getenv('leaftools_dev')) {
                $message .= ': ' . $e->getMessage();
            }
            response()->json(['message' => $message], 500);
        }
    }

    public function logout()
    {

    }

}
