<?php

declare(strict_types=1);

namespace app\models;

use app\types\UUID;
use DateTime;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Leaf\Model;

/**
 * @property int $id
 * @property UUID $token
 * @property User $user
 * @property IotData $data
 * @property DateTime $cumpleanos
 * @property string $name
 * @property string $icon
 * @property string especie
 * @property float last_latitude
 * @property float last_longitude
 */
class IotDevice extends Model
{
    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        "token"          => "",
        "user"           => "",
        "name"           => "",
        "cumpleanos" => "",
        "icon"           => "",
        "especie"        => "",
        "last_latitude"  => null,
        "last_longitude" => null,

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
    protected $fillable = [
        "token",
        "user",
        "name",
        "icon",
        "especie",
        "created_at",
        "updated_at",
        "last_latitude",
        "last_longitude",
        "cumpleanos"
    ];

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

    /**
     * @throws \Exception
     */
    public function getCumpleanosAttribute($value): DateTime
    {
        return new DateTime($value);
    }

    public function setCumpleanosAttribute(DateTime $date): void
    {
        $this->attributes['cumpleanos'] = $date->format($this->dateFormat);
    }

}
