<?php

use App\Models\User;
use App\Controllers\UsersController;

/*
 * Todas las rutas REQUIEREN autenticación basada en Bearer token.
 */

// INDEX - Mostrar todos los usuarios
app()->get('/users', 'UsersController@index');

// STORE - Crear un nuevo usuario
app()->post('/users', 'UsersController@store');

// SHOW - Mostrar un usuario existente
app()->get('/users/{id}', 'UsersController@show');

// POST/PUT/PATCH - Actualizar un usuario existente
app()->put('/users/{id}', 'UsersController@update');
app()->patch('/users/{id}', 'UsersController@update');
app()->post('/users/{id}', 'UsersController@update');

// DELETE - Eliminar un usuario existente
app()->delete('/users/{id}', 'UsersController@destroy');
