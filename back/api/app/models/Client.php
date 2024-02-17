<?php

namespace app\models;

use app\types\IPv4;
use app\types\UUID;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Client
 * @property int $id
 * @property IPv4 $ipv4
 * @property UUID $token
 * @property bool $locked
 * @property User $user
 * @package App\Models
 */
class Client extends Model
{

    protected $attributes = [
        "ipv4"   => 0,
        "token"  => "",
        "locked" => false,
        "user" => 0,
    ];

    protected $dateFormat = "Y-m-d H:i:s";
    protected $table = "clients";
    protected $primaryKey = "id";
    public $incrementing = true;

    protected $fillable = [
        "id",
        "ipv4",
        "token",
        "locked",
        "user",
    ];

    public $timestamps = true;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user');
    }

    public function getIpv4Attribute($value): IPv4
    {
        return new IPv4($value);
    }

    public function setIpv4Attribute(IPv4 $ipv4): void
    {
        $this->attributes['ipv4'] = $ipv4;
    }

    public function getTokenAttribute($value): UUID
    {
        return new UUID($value);
    }

    public function setTokenAttribute(UUID $uuid): void
    {
        $this->attributes['token'] = $uuid;
    }

    public function setLockedAttribute($value)
    {
        $this->attributes['locked'] = $value == "true" ? 1 : 0;
    }

    public function getLockedAttribute($value)
    {
        return $value == 1 ? "true" : "false";
    }


}
