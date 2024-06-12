<?php

declare(strict_types=1);

namespace app\models;

use Leaf\Model;

class Log extends Model {

    protected $attributes = [
        "user" => 0,
        "client" => 0,
        "message" => "",
    ];

    protected $dateFormat = "Y-m-d H:i:s";
    protected $table = "log";
    protected $primaryKey = "id";
    public $incrementing = true;

    protected $fillable = [
        "user",
        "client",
        "message",
    ];

    public $timestamps = true;

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function client(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
