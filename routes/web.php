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

});

$router->group(['prefix' => 'api/v1','middleware' => 'jwt.auth'], 
    function() use ($router) {

        $router->get('admin/user',['as'=>'user.index','middleware'=>'req.permission:see_user','uses' => 'Admin\UserController@index']);
        $router->post('admin/user',['as'=>'user.store','middleware'=>'req.permission:create_user','uses' => 'Admin\UserController@store']);

    }
);