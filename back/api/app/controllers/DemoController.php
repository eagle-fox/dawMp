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

        $totalUsers = 128;
        $totalClientsPerUser = 5;
        $totalDevicesPerUser = 128;
        $totalDataPerDevice = 128;

        $totalOperations = $totalUsers * ($totalClientsPerUser + $totalDevicesPerUser * $totalDataPerDevice);
        $completedOperations = 0;
        $etaFormatted = "Calculating...";

        for ($i = 0; $i < $totalUsers; $i++) {
            $user = new User();
            $user->nombre = $faker->firstName;
            $user->nombre_segundo = $faker->firstName;
            $user->apellido_primero = $faker->lastName;
            $user->apellido_segundo = $faker->lastName;
            $user->email = $faker->unique()->safeEmail;
            $user->password = password_hash('password', PASSWORD_BCRYPT);
            $user->save();
            $completedOperations++;

            for ($j = 0; $j < $totalClientsPerUser; $j++) {
                $client = new Client();
                $client->user = $user->id;
                $client->ipv4 = new IPv4($faker->ipv4);
                $client->token = new UUID();
                $client->save();
                $completedOperations++;
            }

            for ($j = 0; $j < $totalDevicesPerUser; $j++) {
                $device = new IotDevice();
                $device->user = $user->id; // Assign the user's id
                $device->token = new UUID();
                $device->icon = $faker->word;
                $device->name = $faker->word;
                $device->especie = $faker->word;
                $device->save();
                $completedOperations++;

                for ($k = 0; $k < $totalDataPerDevice; $k++) {
                    $iotData = new IotData();
                    $iotData->device = $device->id;
                    $iotData->latitude = $faker->latitude;
                    $iotData->longitude = $faker->longitude;
                    $iotData->save();
                    $completedOperations++;
                    if ($completedOperations % 100 == 0) {
                        $etaSeconds = ($totalOperations - $completedOperations) / $completedOperations * (microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"]);
                        $etaSecondsRounded = round($etaSeconds);
                        $etaFormatted = gmdate("H:i:s", $etaSecondsRounded);
                    }
                    error_log("ETA $etaFormatted  Progress: $completedOperations / $totalOperations");
                }
            }

        }

        response()->json(["message" => "Random data created"]);
    }
}
