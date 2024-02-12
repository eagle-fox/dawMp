<?php

namespace app\controllers;

use app\models\client;
use app\models\Log;
use app\models\user;
use Illuminate\Database\Eloquent\Collection;
use Random\RandomException;

class Utils
{

    /**
     * Log an action in the log table.
     */
    public static function logAction(User $user, Client $client, string $msg): void
    {
        $log = new Log();
        $log->user = $user->id;
        $log->client = $client->id;
        $log->message = $msg;
        $log->save();
    }



}
