<?php
declare(strict_types=1);
namespace app\types;
class Point {
    private float $latitude;
    private float $longitude;

    public function __construct($latitude, $longitude) {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    public function getLatitude() {
        return $this->latitude;
    }

    public function getLongitude() {
        return $this->longitude;
    }

    public function __toString() {
        return 'POINT(' . $this->latitude . ' ' . $this->longitude . ')';
    }
}
