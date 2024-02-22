<?php

namespace app\controllers;

use app\models\Client;
use app\models\IotDevice;
use app\models\User;
use app\models\IotData;
use app\types\IPv4;
use app\types\UUID;
use Faker\Factory;

class DemoController extends Controller
{
    public function create(): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->nombre = $faker->firstName;
            $user->nombre_segundo = $faker->firstName;
            $user->apellido_primero = $faker->lastName;
            $user->apellido_segundo = $faker->lastName;
            $user->email = $faker->unique()->safeEmail;
            $user->password = password_hash('password', PASSWORD_BCRYPT);
            $user->save();

            for ($j = 0; $j < 3; $j++) {
                $client = new Client();
                $client->user = $user->id;
                $client->ipv4 = new IPv4($faker->ipv4);
                $client->token = new UUID();
                $client->save();
                error_log("Client created: " . $client->id);
            }

            error_log("User created: " . $user->id);
            for ($j = 0; $j < 10; $j++) {
                $device = new IotDevice();
                $device->user = $user->id; // Assign the user's id
                $device->token = new UUID();
                $device->icon = $faker->word;
                $device->name = $faker->word;
                $device->especie = $faker->word;
                $device->save();
                error_log("Device created: " . $device->id);

                for ($k = 0; $k < 1024; $k++) {
                    $iotData = new IotData();
                    $iotData->device = $device->id;
                    $iotData->latitude = $faker->latitude;
                    $iotData->longitude = $faker->longitude;
                    $iotData->save();
                }
                error_log("Data created: " . $iotData->id);
            }
        }

        response()->json(["message" => "Random data created"]);
    }
}
