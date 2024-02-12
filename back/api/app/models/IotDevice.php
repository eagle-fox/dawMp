<?php

declare(strict_types=1);

namespace App\Models;

use Leaf\Model;

class IotDevice extends Model {
    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        "uuid" => "",
        "token" => "",
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
    protected $fillable = ["id", "uuid", "token"];

    /**
     * Todas va con sellado de tiempo.
     *
     * @var bool
     */
    public $timestamps = true;
}
