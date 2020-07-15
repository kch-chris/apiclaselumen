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


$router->group(['prefix' => 'api/v1/'], function() use ($router) {
    // rutas de recursos de acceso
    $router->post('auth/login', ['as' => 'login', 'uses' => 'Auth\LoginController@authenticate']);

    $router->get('admin/user',['as'=>'user.index','uses' => 'Admin\UserController@index']);
    $router->post('admin/user',['as'=>'user.store','uses' => 'Admin\UserController@store']);
    $router->put('admin/user/{id}',['as'=>'user.update','uses' => 'Admin\UserController@update']);
    $router->delete('admin/user/{id}',['as'=>'user.destroy','uses' => 'Admin\UserController@destroy']);

}
);