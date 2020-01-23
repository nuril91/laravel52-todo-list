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

Route::get('/', 'TaskController@index');

Route::post('add-task', 'TaskController@store')->name('add-task');

Route::put('update-task/{id}', 'TaskController@update')->name('update-task');

Route::delete('delete-task/{id}', 'TaskController@destroy')->name('delete-task');