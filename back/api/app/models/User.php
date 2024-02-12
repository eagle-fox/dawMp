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
    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['clients'];

    /**
     * Get the clients for the user.
     */
    public function clients()
    {
        return $this->hasMany(Client::class, 'user');
    }
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
    public function getFillableFields(): array
    {
        return $this->fillable;
    }
}
