<?php

use App\Models\User;
use App\Controllers\UsersController;

/*
 * Todas las rutas REQUIEREN autenticación basada en Bearer token.
 */

// INDEX - Mostrar todos los usuarios
app()->get("/iotDevices", "iotDevicesController@index");

// STORE - Crear un nuevo usuario
app()->post("/iotDevices", "iotDevicesController@store");

// SHOW - Mostrar un usuario existente
app()->get("/iotDevices/{id}", "iotDevicesController@show");

// POST/PUT/PATCH - Actualizar un usuario existente
app()->put("/iotDevices/{id}", "iotDevicesController@update");
app()->patch("/iotDevices/{id}", "iotDevicesController@update");
app()->post("/iotDevices/{id}", "iotDevicesController@update");

// DELETE - Eliminar un usuario existente
app()->delete("/iotDevices/{id}", "iotDevices@destroy");
