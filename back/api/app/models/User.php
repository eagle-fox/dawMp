<?php

declare(strict_types=1);

namespace App\Models;

/**
 * Class User
 *
 * @package App\Models
 */
class user extends Model
{
    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'nombre' => '',
        'nombre_segundo' => null,
        'apellido_primero' => '',
        'apellido_segundo' => '',
        'email' => '',
        'password' => '',
        'rol' => Rol::GUEST,
    ];

    /**
     * Formato compatible con DateTime de MySQL.
     *
     * @var string
     */
    protected $dateFormat = 'Y-m-d H:i:s';

    /**
     * Exactamente el nombre de la tabla que se va a utilizar desde el DDL.
     *
     * @var string
     */
    protected $table = 'user';

    /**
     * La clave primaria, por defecto, es 'id'.
     *
     * @var int
     */
    protected $primaryKey = 'id';

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
        'id', 'nombre', 'nombre_segundo', 'apellido_primero', 'apellido_segundo', 'email', 'password', 'rol',
    ];

    /**
     * Los atributos que deben ser ocultados para serialización etc.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Todas va con sellado de tiempo.
     *
     * @var bool
     */
    public $timestamps = true;

}
