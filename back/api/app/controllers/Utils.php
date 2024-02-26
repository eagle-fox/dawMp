<?php

namespace app\controllers;

use app\models\client;
use app\models\user;
use Illuminate\Database\Eloquent\Collection;
use Random\RandomException;

class Utils
{
    static function calculateNewCoordinates(float $latitude,float $longitude,float $distance): array {
        $latMid = $latitude; // as we are calculating for this point, we can use the latitude directly

        $m_per_deg_lat = 111132.954 - 559.822 * cos(2.0 * deg2rad($latMid)) + 1.175 * cos(4.0 * deg2rad($latMid));
        $m_per_deg_lon = 111132.954 * cos(deg2rad($latMid));

        $deltaLat = $distance / $m_per_deg_lat;
        $deltaLon = $distance / $m_per_deg_lon;

        $max_latitude = $latitude + $deltaLat;
        $min_latitude = $latitude - $deltaLat;

        $max_longitude = $longitude + $deltaLon;
        $min_longitude = $longitude - $deltaLon;

        return ['max_latitude' => $max_latitude, 'min_latitude' => $min_latitude, 'max_longitude' => $max_longitude, 'min_longitude' => $min_longitude];
    }
}
