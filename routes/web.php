<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
  return $router->app->version();
});

$router->post('auth/login', ['uses' => 'AuthController@authenticate']);

$router->group(['prefix' => 'api'], function () use ($router) {

  /**
   * User endpoints
   */

  $router->group(['middleware' => 'jwt.auth'], function () use ($router) {

    $router->get('users', ['uses' => 'UserController@showAllUsers']);
  });

  /**
   * Event endpoints
   */
  $router->get('events', ['uses' => 'EventController@showAllEvents']);

  $router->get('events/{code}', ['uses' => 'EventController@showOneEvent']);

  $router->post('events', ['uses' => 'EventController@create']);

  $router->put('events/{id}', ['uses' => 'EventController@update']);

  $router->delete('events/{id}', ['uses' => 'EventController@delete']);

  $router->get('events/{id}/guests', ['uses' => 'EventController@getGuests']);

  $router->post('events/{id}/guests', ['uses' => 'EventController@addGuests']);
});
