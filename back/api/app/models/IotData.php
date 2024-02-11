<?php

declare(strict_types=1);

namespace app\models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Leaf\Model;

class IotData extends Model
{
    public int $id;
    public int $device;
    public float $latitude;
    public float $longitude;

    protected $attributes = [
        "device"    => 0,
        "latitude"  => 0.0,
        "longitude" => 0.0,
    ];

    protected $dateFormat = "Y-m-d H:i:s";
    protected $table = "iot_data";
    protected $primaryKey = "id";
    public $incrementing = true;

    protected $fillable = [
        "id",
        "device",
        "latitude",
        "longitude",
    ];

    public $timestamps = true;

    public function device(): BelongsTo
    {
        return $this->belongsTo(IotDevice::class);
    }
}
