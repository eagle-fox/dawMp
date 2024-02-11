<?php

declare(strict_types=1);

namespace App\Models;

/**
 * Class User
 *
 * @package App\Models
 */class User extends Model {

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
