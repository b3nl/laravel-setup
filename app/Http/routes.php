<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::group(['namespace' => 'Setup', 'prefix' => 'setup'], function () {
    Route::resource('config', 'ConfigController', ['except' => ['create', 'destroy', 'edit', 'show', 'update']]);
});

Route::get('/', function () {
    return view('welcome');
});
