<?php
declare(strict_types=1);

require_once __DIR__ . "/user.php";
require_once __DIR__ . "/iotDevices.php";
require_once __DIR__ . "/iotData.php";

use App\Models\User;
use App\Controllers\UsersController;

app()->get("/", function () {
    $user = new User();
    $user->nombre = "John";
    $user->nombre_segundo = "Doe";
    response()->json(["message" => 'Congrats!! You\'re on Leaf API']);
});

app()->get("/demo","DemoController@create");
