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

Route::get('/trainers', 'API\\TrainerController@all')->name('trainers');

Route::get('/trainers/{trainerId}/appointments', 'API\\TrainerController@getAppointmentsById')
    ->name('trainers.appointments');

Route::get('/trainers/{trainerId}/schedules', 'API\\TrainerController@getSchedule')
    ->name('trainers.schedule');

Route::get('/clients/{clientId}/appointments', 'API\\ClientController@getAppointmentById')
    ->name('clients.appointments');

Route::group([
    'middleware' => 'auth:clients',
], function ($router) {
    Route::get('/client/appointments', 'API\\ClientController@getMyAppointments')
        ->name('client.appointments');

    Route::post('/trainers/{trainerId}/appointments', 'API\\TrainerController@bookAppointment')
        ->name('client.appointments.book');

    Route::delete('/client/appointments/{appointmentId}', 'API\\ClientController@removeAppointment')
        ->name('client.appointments.delete');
});
//Route::match(['get', 'post', 'put', 'delete'], '/unauthorized', function () {
//    return response()->json(['error' => 'Unauthorized'], 401);
//})->name('api.login');
