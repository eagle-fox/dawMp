<?php

declare(strict_types=1);

namespace App\Models;

/**
 * Class User
 *
 * @package App\Models
 */
class user extends Model {
    public int $id;
    public string $token;
    public string $nombre;
    public string $nombre_segundo;
    public string $apellido_primero;
    public string $apellido_segundo;
    public string $email;
    public string $password;
    public string $rol;
    public bool $locked;

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        "nombre" => "",
        "nombre_segundo" => null,
        "apellido_primero" => "",
        "apellido_segundo" => "",
        "email" => "",
        "password" => "",
        "rol" => Rol::GUEST,
        "token" => null,
        "locked" => false,
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
    protected $table = "user";

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
        "id",
        "nombre",
        "nombre_segundo",
        "apellido_primero",
        "apellido_segundo",
        "email",
        "password",
        "rol",
        "token",
        "locked",
    ];

    public function getFillable() {
        return $this->fillable;
    }

    /**
     * Los atributos que deben ser ocultados para serialización etc.
     *
     * @var array
     */
    protected $hidden = ["password"];

    /**
     * Todas va con sellado de tiempo.
     *
     * @var bool
     */
    public $timestamps = true;


}
