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
        /*
        |--------------------------------------------------------------------------
        |
        | This is an example which retrieves all the data (rows)
        | from our model. You can un-comment it to use this
        | example
        |
        */
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id)
    {
        /*
        |--------------------------------------------------------------------------
        |
        | This is an example which edits a particular row.
        | You can un-comment it to use this example
        |
        */ // $row = User::find($id);
        // $row->column = request()->get('column');
        // $row->save();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        /*
        |--------------------------------------------------------------------------
        |
        | This is an example which deletes a particular row.
        | You can un-comment it to use this example
        |
        */ // $row = User::find($id);
        // $row->delete();
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
                response()->json($user);
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
                response()->json($user);
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
}
