<?php
declare(strict_types=1);
namespace app\models;

use app\types\Email;
use app\types\Password;
use app\types\Rol;
use Illuminate\Database\Eloquent\Relations\HasMany;
use InvalidArgumentException;

/**
 * Class User
 *
 * @property Rol $rol
 * @property string $nombre
 * @property string $nombre_segundo
 * @property string $apellido_primero
 * @property string $apellido_segundo
 * @property Email $email
 * @property Password $password
 * @property Client[] $clients
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
    public function clients(): HasMany
    {
        return $this->hasMany(Client::class, 'user');
    }

    public function setRolAttribute($rol)
    {
        if (is_string($rol)) {
            $rol = Rol::from($rol);
        }

        if (!$rol instanceof Rol) {
            throw new InvalidArgumentException('Invalid type for rol');
        }

        $this->attributes['rol'] = $rol;
    }

    public function getRolAttribute($rol)
    {
        if ($rol instanceof Rol) {
            $rol = $rol->getValue(); // Assuming Rol has a getValue() method that returns the string representation
        }

        if (!is_string($rol)) {
            throw new InvalidArgumentException('Invalid type for rol');
        }

        return Rol::from($rol);
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
