<?php
declare(strict_types=1);

require_once __DIR__ . '/user.php';

use App\Models\User;
use App\Controllers\UsersController;

app()->get('/', function () {
    response()->json(['message' => 'Congrats!! You\'re on Leaf API']);
});
