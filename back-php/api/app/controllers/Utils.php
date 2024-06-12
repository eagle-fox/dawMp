<?php

namespace app\controllers;

class Utils
{
    /**
     * Calcula las nuevas coordenadas basándose en una latitud y longitud dadas, y una distancia.
     *
     * Este método utiliza álgebra lineal para calcular las nuevas coordenadas. Primero, calcula
     * los metros por grado de latitud y longitud en la latitud dada. Luego, calcula el cambio en
     * latitud y longitud dividiendo la distancia por los metros por grado de latitud y longitud,
     * respectivamente. Finalmente, calcula las nuevas latitudes y longitudes sumando y restando
     * el cambio en latitud y longitud a la latitud y longitud dadas, respectivamente.
     *
     * @param float $latitude La latitud actual.
     * @param float $longitude La longitud actual.
     * @param float $distance La distancia a añadir o restar a la latitud y longitud actuales.
     * @return array Un array asociativo con las nuevas latitudes y longitudes máximas y mínimas.
     */
    static function calculateNewCoordinates(float $latitude, float $longitude, float $distance): array
    {

        $latMid = $latitude;

        $m_per_deg_lat = 111132.954 - 559.822 * cos(2.0 * deg2rad($latMid)) + 1.175 * cos(4.0 * deg2rad($latMid));
        $m_per_deg_lon = 111132.954 * cos(deg2rad($latMid));

        $deltaLat = $distance / $m_per_deg_lat;
        $deltaLon = $distance / $m_per_deg_lon;

        $max_latitude = $latitude + $deltaLat;
        $min_latitude = $latitude - $deltaLat;

        $max_longitude = $longitude + $deltaLon;
        $min_longitude = $longitude - $deltaLon;

        return [
            'max_latitude'  => $max_latitude,
            'min_latitude'  => $min_latitude,
            'max_longitude' => $max_longitude,
            'min_longitude' => $min_longitude
        ];
    }
}
