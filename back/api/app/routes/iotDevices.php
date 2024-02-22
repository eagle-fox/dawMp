<?php

use App\Models\User;
use App\Controllers\UsersController;

/*
 * Todas las rutas REQUIEREN autenticación basada en Bearer token.
 */

// INDEX - Mostrar todos los usuarios
app()->get("/iotDevices", "IotDevicesController@index");

// STORE - Crear un nuevo usuario
app()->post("/iotDevices", "IotDevicesController@store");

// SHOW - Mostrar un usuario existente
app()->get("/iotDevices/{id}", "IotDevicesController@show");

// POST/PUT/PATCH - Actualizar un usuario existente
app()->put("/iotDevices/{id}", "IotDevicesController@update");
app()->patch("/iotDevices/{id}", "IotDevicesController@update");
app()->post("/iotDevices/{id}", "IotDevicesController@update");

// DELETE - Eliminar un usuario existente
app()->delete("/iotDevices/{id}", "IotDevicesController@destroy");
