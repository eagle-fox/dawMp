<?php
declare(strict_types=1);

use app\mail\Email;
use app\mail\EmailConnector;
use app\models\IotDevice;
use app\mail\Dinahosting;
use Faker\Factory;

require_once __DIR__ . "/user.php";
require_once __DIR__ . "/iotDevices.php";
require_once __DIR__ . "/iotData.php";


app()->get("/", function () {
    response()->json(["message" => 'Congrats!! You\'re on Leaf API']);
});

app()->get("/demo", "DemoController@create");
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

app()->get("/testemail", function () {
    $fakers = [
        Factory::create('es_ES'),
        Factory::create('en_UK'),
        Factory::create('pt_PT'),
        Factory::create('de_DE'),
    ];

    $msg = "";

    for ($i = 0; $i < 4; $i++) {
        $faker = $fakers[$i];
        if ($i === 0) {
            $language = 'es';
        } else if ($i === 1) {
            $language = 'en';
        } else if ($i === 2) {
            $language = 'pt';
        } else {
            $language = 'de';
        }

        $msg .= "<h1>Idioma: " . strtoupper($language) . "</h1>";
        $msg .= "<h2>Nombre: " . $faker->name . "</h2>";
        $msg .= "<p>" . $faker->realText(1024) . "</p>";

        $avatarUrl = $faker->imageUrl(100, 100, 'people');
        $msg .= "<p><img src='{$avatarUrl}' alt='Avatar aleatorio'></p>";
    }

    $email = new Dinahosting();
    $email->enviar(
        'yeisonrascado@gmail.com',
        'Prueba de PHP Mailer',
        $msg
    );

    response()->json(["message" => "Email sent"]);
});
