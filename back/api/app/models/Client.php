<?php

namespace app\models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Client extends Model
{

    protected $attributes = [
        "ipv4"   => 0,
        "token"  => "",
        "locked" => false,
        "client" => 0,
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
        "client",
    ];

    public $timestamps = true;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client');
    }

    /**
     * Since the ipv4 is stored as an integer, we need to convert it to a string to be "clear" for the user.
     * @param $value
     * @return false|string
     */
    public function getIpv4Attribute($value)
    {
        return long2ip($value);
    }

    public function setIpv4Attribute($value)
    {
        $this->attributes['ipv4'] = ip2long($value);
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
