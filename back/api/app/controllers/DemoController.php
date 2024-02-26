<?php

namespace app\controllers;
ini_set('max_execution_time', 0);
ini_set('memory_limit', '4096M');

use app\models\Client;
use app\models\IotData;
use app\models\IotDevice;
use app\models\User;
use app\types\IPv4;
use app\types\UUID;
use Faker\Factory;

class DemoController extends Controller
{
    public function create(): void
    {
        $fakers = [
            Factory::create('es_ES'),
            Factory::create('en_UK'),
            Factory::create('pt_PT'),
            Factory::create('de_DE'),
        ];

        $totalUsers = 128;
        $totalClientsPerUser = 128;
        $totalDevicesPerUser = 128;
        $totalDataPerDevice = 128;

        $totalOperations = $totalUsers * ($totalClientsPerUser + $totalDevicesPerUser * $totalDataPerDevice);
        $completedOperations = 0;
        $etaFormatted = "Calculating...";

        for ($i = 0; $i < $totalUsers; $i++) {
            $faker = $fakers[$i % 4];
            $user = new User();
            $user->nombre = $faker->firstName;
            $user->nombre_segundo = $faker->firstName;
            $user->apellido_primero = $faker->lastName;
            $user->apellido_segundo = $faker->lastName;
            $user->email = $faker->unique()->safeEmail;
            $user->password = hash("sha256", 'userpassword');
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
                        $insertionsPerSecond = $completedOperations / (microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"]);
                        error_log("ETA $etaFormatted  Progress: % " . round($completedOperations / $totalOperations * 100, 2) . " Insertions per second: " . round($insertionsPerSecond, 2));
                    }
                }
            }

        }

        response()->json(["message" => "Random data created"]);
    }
}
