<?php

declare(strict_types=1);

namespace app\models;

use app\types\UUID;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Leaf\Model;

/**
 * @property int $id
 * @property UUID $token
 * @property User $user
 * @property IotData $data
 * @property string $name
 * @property string $icon
 * @property string especie
 */
class IotDevice extends Model
{
    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = ["token" => "",
        "user" => "", "name" => "",
        "icon" => "",
        "especie" => ""
    ];

    /**
     * Formato compatible con DateTime de MySQL.
     *
     * @var string
     */
    protected $dateFormat = "Y-m-d H:i:s";

    /**
     * Exactamente el nombre de la tabla que se va a utilizar desde el DDL.
     *
     * @var string
     */
    protected $table = "iot_devices";

    /**
     * La clave primaria, por defecto, es 'id'.
     *
     * @var int
     */
    protected $primaryKey = "id";

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * Los atributos accesibles para el modelo en masa.
     *
     * @var array
     */
    protected $fillable = ["token", "user", "name", "icon", "especie"];

    /**
     * Todas va con sellado de tiempo.
     *
     * @var bool
     */
    public $timestamps = true;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function data(): HasMany
    {
        return $this->hasMany(IotData::class, 'device');
    }

    public function getFillable(): array
    {
        return $this->fillable;
    }

    public function getTokenAttribute($value): UUID
    {
        return new UUID($value);
    }

    public function setTokenAttribute(UUID $uuid): void
    {
        $this->attributes['token'] = $uuid;
    }

    public function iotData()
    {
        return $this->hasMany(IotData::class, 'device');
    }

}
