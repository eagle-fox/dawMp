<?php

namespace app\routes;

use app\middlewares\MiddlewareUser;
use app\models\IotData;
use app\models\IotDevice;
use app\types\Rol;
use Exception;

app()->post("/iotData/historical",function (){

    try {
        $auth = new MiddlewareUser(Rol::USER);
        $user = $auth->getUser();
        $devices = IotDevice::query()->find(app()->request()->get("id"));
        if (!$devices instanceof IotDevice) {
            app()->response()->json(["message" => "Device not found"], 404);
            return;
        }
        if ($devices->user !== $user->id) {
            app()->response()->json(["message" => "Unauthorized"], 401);
            return;
        }


    } catch (Exception $e) {
        app()->response()->json(["message" => "Unauthorized"], 401);
        return;
    }

    try {
        $dateStart = app()->request()->get("dateStart");
        $dateEnd = app()->request()->get("dateEnd");
        $id = app()->request()->get("id");
    } catch (Exception $e) {
        app()->response()->json(["message" => "Debes facilitar una fecha de inicio y una fecha de fin"], 400);
        return;
    }

    $data = IotData::query()->where("device", $id)->whereBetween("created_at", [$dateStart, $dateEnd])->get();

    if (!$data[0] instanceof IotData) {
         app()->response()->json(["message" => "No data found"], 404);
         return;
     }

    app()->response()->json(["message" => "All data", "data" => $data]);

});

app()->get("/iotData", "IotDataController@index");
app()->post("/iotData", "IotDataController@store");
app()->get("/iotData/{id}", "IotDataController@show");
app()->put("/iotData/{id}", "IotDataController@update");
app()->patch("/iotData/{id}", "IotDataController@update");
app()->post("/iotData/{id}", "IotDataController@update");
app()->delete("/iotData/{id}", "IotDataController@destroy");
