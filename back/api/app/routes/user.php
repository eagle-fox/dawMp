<?php

use App\Models\User;
use App\Controllers\UsersController;

app()->resource('/users', 'UsersController');
app()->put('/users/{id}', 'UsersController@update');
/**
* Endpoint to create temporary by email and password
* @param string $email
* @param string $password
*/
app()->post('/users/loginByEmailAndPassword', 'UsersController@loginByEmailAndPassword');

/**
* Endpoint to login by token and email
* @param string $token
* @param string $email
*/
app()->post('/users/loginByTokenAndEmail', 'UsersController@loginByTokenAndEmail');

/**
 * Endpoint to login by token
 * @param string $token
 */
app()->post('/users/loginByToken', 'UsersController@loginByToken');

/**
 * Endpoint to logout
 * @param string $token
 */
app()->post('/users/logout', 'UsersController@logout');
