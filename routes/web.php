<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

// Route Group untuk API
$router->group(['prefix' => 'api'], function () use ($router) {
    
    // Auth Routes
    $router->post('/register', 'API\AuthController@register');
    $router->post('/login', 'API\AuthController@login');

    // Route yang butuh Auth
    $router->group(['middleware' => 'auth'], function () use ($router) {
        
        $router->get('/logs', 'API\StockLogController@index');
        $router->post('/logout', 'API\AuthController@logout');

        $router->get('/user', function () {
            return auth()->user();
        });

        $router->get('/items', 'API\ItemController@index');          // Get all
        $router->post('/items', 'API\ItemController@store');         // Create
        $router->get('/items/{id}', 'API\ItemController@show');      // Get 
        $router->put('/items/{id}', 'API\ItemController@update');    // Update
        $router->delete('/items/{id}', 'API\ItemController@destroy'); // Delete

        $router->get('/categories', 'API\CategoriesController@index');          // Get all
        $router->post('/categories', 'API\CategoriesController@store');         // Create
        $router->get('/categories/{id}', 'API\CategoriesController@show');      // Get single
        $router->put('/categories/{id}', 'API\CategoriesController@update');    // Update
        $router->delete('/categories/{id}', 'API\CategoriesController@destroy'); // Delete

        $router->get('/suppliers', 'API\SupplierController@index');          // Get all
        $router->post('/suppliers', 'API\SupplierController@store');         // Create
        $router->delete('/suppliers/{id}', 'API\SupplierController@destroy'); // Delete
    });
});