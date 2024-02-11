<?php

namespace app\models;

class Client extends Model {

    protected $attributes = [
        "ipv4" => 0,
        "token" => "",
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

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'client');
    }
}
