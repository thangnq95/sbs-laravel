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

Route::get('/', function () {
    return view('welcome');
});
//Route::get('user/{id}', 'UserController@show');
//Route::get('user/{id}', 'UserController@update');
Route::get('user-invokable/{id}', 'ShowProfile');
Route::get('/greeting', function () {
    return view('greeting', ['name' => 'Thang']);
});
Route::get('/greeting1', function () {
    return view('admin.profile', ['name' => 'Thang1']);
});
Route::get('/greeting2', function () {
    return view('greeting')->with('name', 'Thang2');
});
Route::get('user/profile', 'UserController@profile');

