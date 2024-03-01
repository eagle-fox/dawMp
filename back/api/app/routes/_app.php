<?php
declare(strict_types=1);

use app\models\IotDevice;

require_once __DIR__ . "/user.php";
require_once __DIR__ . "/iotDevices.php";
require_once __DIR__ . "/iotData.php";


app()->get("/", function () {
    response()->json(["message" => 'Congrats!! You\'re on Leaf API']);
});

app()->get("/demo","DemoController@create");
app()->get("/cumpleaños", function () {
        // get all IoT Devices
        $devices = IotDevice::query()->get();
        foreach ($devices as $device) {
            // set a random creation date
            $device->created_at = date("Y-m-d H:i:s", rand(0, time()));
            $device->save();
            error_log("Device updated: " . json_encode($device));
        }
        response()->json(["message" => "Cumpleaños actualizados"]);
});
