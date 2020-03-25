<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
*/

/* Signup & Login */
$router->post('auth/login', ['uses' => 'AuthController@authenticate']);
$router->post('signup', ['uses' => 'SignupController@create']);

$router->get('api/events/{code}', ['uses' => 'EventController@showOneEvent']);
$router->get('api/events/{eventCode}/guests', ['uses' => 'EventController@getGuests']);
$router->post('api/events/{eventCode}/guests', ['uses' => 'EventController@addGuests']);
$router->post('api/events/{eventCode}/needs', ['uses' => 'EventController@addNeed']);
$router->put('api/events/{eventCode}/needs/{id}', ['uses' => 'EventController@updateNeed']);
$router->get('api/events/{eventCode}/needs', ['uses' => 'EventController@getNeeds']);
$router->delete('api/events/{eventCode}/needs/{id}', ['uses' => 'EventController@removeNeed']);

/* User endpoints */
$router->get('api/users', ['uses' => 'UserController@showAllUsers']);
$router->get('api/users/{id}', ['uses' => 'UserController@showOneUser']);
$router->put('api/users/{id}', ['uses' => 'UserController@updateUser']);
$router->get('api/users/{id}/events', ['uses' => 'UserController@getEvents']);
$router->delete('api/users/{id}', ['uses' => 'UserController@destroy']);

/* Event endpoints */
$router->get('api/events', ['uses' => 'EventController@showAllEvents']);
$router->post('api/events', ['uses' => 'EventController@create']);
$router->put('api/events/{eventCode}', ['uses' => 'EventController@update']);
$router->delete('api/events/{id}', ['uses' => 'EventController@delete']);
$router->get('api/events/{eventCode}/comments', ['uses' => 'EventController@getComments']);
$router->post('api/events/{eventCode}/comments', ['uses' => 'EventController@addComment']);
