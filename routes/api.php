<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/* Login routes */
Route::group(['prefix' => '/{type}', 'where' => ['type' => 'clients|trainers']], function ($router) {
    $router->post('/login', 'API\\AuthenticationController@login')
        ->name('api.login');

    $router->get('/me', 'API\\AuthenticationController@me')
        ->name('api.me');
});

/* Trainer controller */
Route::put("/trainer", "API\\TrainerController@update")
    ->middleware('auth:trainers')
    ->name('api.trainer');


//Route::match(['get', 'post', 'put', 'delete'], '/unauthorized', function () {
//    return response()->json(['error' => 'Unauthorized'], 401);
//})->name('api.login');
