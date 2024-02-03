<?php

namespace App\Controllers;

use App\Models\User;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {
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
        $user = new User;
        $user->nombre = request()->get('nombre');
        $user->nombre_segundo = request()->get('nombre_segundo');
        $user->apellido_primero = request()->get('apellido_primero');
        $user->apellido_segundo = request()->get('apellido_segundo');
        $user->email = request()->get('email');
        $user->password = request()->get('password');
        $user->rol = request()->get('rol');
        $user->save();
        $this->response->json($user);
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
        */
        // $row = User::find($id);
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
        */
        // $row = User::find($id);
        // $row->delete();
    }
}
