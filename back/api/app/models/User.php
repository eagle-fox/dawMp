<?php

declare(strict_types=1);

namespace App\Models;

/**
 * Class User
 *
 * @package App\Models
 */class User extends Model {
    public int $id;
    public string $nombre;
    public string $nombre_segundo;
    public string $apellido_primero;
    public string $apellido_segundo;
    public string $email;
    public string $password;
    public string $rol;

    protected $attributes = [
        "nombre" => "",
        "nombre_segundo" => "",
        "apellido_primero" => "",
        "apellido_segundo" => "",
        "email" => "",
        "password" => "",
        "rol" => "USER",
    ];

    protected $dateFormat = "Y-m-d H:i:s";
    protected $table = "user";
    protected $primaryKey = "id";
    public $incrementing = true;

    protected $fillable = [
        "id",
        "nombre",
        "nombre_segundo",
        "apellido_primero",
        "apellido_segundo",
        "email",
        "password",
        "rol",
    ];

    public $timestamps = true;
}
