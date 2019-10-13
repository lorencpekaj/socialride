<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Simple route to store the user data
Route::get('me', function () {
    return response()->json(Auth::user());
});

// Authentication Routes...
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

// Registration Routes...
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');

// Facebook login
Route::prefix('facebook')
    ->group(function () {
        Route::get('/', 'FacebookAuthController@redirect')->name('facebook');
        Route::get('/callback', 'FacebookAuthController@callback');
    });

// Password Reset Routes...
Route::resetPassword();

// User location
Route::resource('user_location', 'UserLocationController');

// Trip
Route::prefix('trip')
    ->middleware('auth')
    ->group(function () {
        Route::post('/request_pickup', 'TripController@requestPickup');
        Route::post('/accept/{trip}', 'TripController@acceptPickup');
        Route::get('/available', 'TripController@availableTrips');
        Route::delete('/{trip}', 'TripController@delete');
    });

// Homepage
Route::get('/', 'HomeController@index')->middleware('auth')->name('home');
