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

Route::get('/', 'CropController@getHome');

//Route::get('/create','MainController@create');
//Route::get('/edit/{id}','MainController@edit');
//Route::patch('/update/{id}','MainController@update');
//Route::post('/store','MainController@store');

Route::get('main/delete/{id}','MainController@delete');

Route::post('upload', 'CropController@postUpload');
Route::post('crop', 'CropController@postCrop');

Route::resource('main','MainController');
