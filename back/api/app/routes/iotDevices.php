<?php

use app\middlewares\MiddlewareUser;
use app\models\IotDevice;
use app\types\Rol;

/*
 * Todas las rutas REQUIEREN autenticación basada en Bearer token.
 */

// Mostrar placas de un usuario
app()->get("/iotDevices/ByMyself", function () {
    try {
        set_time_limit(300);
        ini_set('memory_limit', '1024M');

        $auth = new MiddlewareUser(Rol::USER);
        $currUser = $auth->user;
        $devices = IotDevice::query()->where('user', $currUser->id)->get();
        error_log("Devices: " . json_encode($devices));
        $data = [];

        foreach ($devices as $device) {
            $data[] = $device;
        }
        response()->json(["message" => "Data found", "data" => $data]);
    } catch (Exception $e) {
        $message = "Error al obtener los datos";
        if (getenv("LEAF_DEV_TOOLS")) {
            $message .= ": " . $e->getMessage();
        }
        response()->json(["message" => $message], 500);
    }
});

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
