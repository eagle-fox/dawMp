<?php
declare(strict_types=1);

require_once __DIR__ . "/user.php";
require_once __DIR__ . "/iotDevices.php";
require_once __DIR__ . "/iotData.php";


app()->get("/", function () {
    response()->json(["message" => 'Congrats!! You\'re on Leaf API']);
});

app()->get("/demo","DemoController@create");
