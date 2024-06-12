<?php

declare(strict_types=1);

namespace app\models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Leaf\Model;

/**
 * Class IotData
 *
 * @property int $id
 * @property float $latitude
 * @property float $longitude
 * @property IotDevice $device
 * @package App\Models
 */

class IotData extends Model
{

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
        "device",
        "latitude",
        "longitude",
    ];

    public $timestamps = true;

    public function device(): BelongsTo
    {
        return $this->belongsTo(IotDevice::class, 'device');
    }

}
