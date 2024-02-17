<?php

declare(strict_types=1);

namespace app\models;

use app\types\UUID;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Leaf\Model;

/**
 * @property UUID $uuid
 * @property User $user
 * @property IotData $data
 */
class IotDevice extends Model {
    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        "uuid" => "",
        "user" => "",
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
    protected $fillable = ["uuid", "user"];

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
}
