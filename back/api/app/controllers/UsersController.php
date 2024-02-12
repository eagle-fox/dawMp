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
        $user = user::query()->get();
        response()->json($user);
    }

    /**
     * Para crear un usuario, se necesita ser administrador o no estar bloqueado por IP.
     * @return void
     */
    public function store(): void
    {
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
    }

    /**
     * Update the specified resource in storage.
     * @throws RandomException
     */
    public function update($id): void
    {
    }

    /**
     * Remove the specified resource from storage.
     * @throws RandomException
     */
    public function destroy($id): void
    {

    }




}
