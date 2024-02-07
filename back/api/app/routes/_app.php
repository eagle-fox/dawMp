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
/**
 * Endpoint to create temporary by email and password
 * @param string $email
 * @param string $password
 *
 * @return user | false
 */
app()->post('/users/loginByEmailAndPassword', 'UsersController@login');

/**
 * Endpoint to login by token and email
 * @param string $token
 * @param string $email
 *
 * @return user | false
 */
app()->post('/users/loginByTokenAndEmail', 'UsersController@loginByTokenAndEmail');
