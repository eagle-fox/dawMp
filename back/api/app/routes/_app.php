<?php
declare(strict_types=1);

use App\Models\User;
use App\Controllers\UsersController;

app()->get('/', function () {
    response()->json(['message' => 'Congrats!! You\'re on Leaf API']);
});

app()->get('/v0/dumpTables', function () {
    db()->autoConnect();
    $tables = db()->query("SHOW TABLES");
    $tables = $tables->fetchAll();
    response()->json($tables);
});

app()->resource('/users', 'UsersController');
