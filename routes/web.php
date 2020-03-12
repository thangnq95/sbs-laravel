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

#ROUTING
Route::get('basic/routing/{id}', 'UserController@show');

#REQUESTS
Route::get('basic/request/{id}', 'UserController@update'); // Test with http://localhost:8000/basic/request/2?name=Thang

#CONTROLLER
Route::get('basic/controller/single-action/{id}', 'ShowProfileController');

#VIEW
Route::get('/baisc/view/normal', function () {
    return view('admin.profile', ['name' => 'Thang1']);
});
Route::get('baisc/view/share', function () {
    return view('greeting')->with('name', 'Thang2');
});
Route::get('baisc/view/composer', 'UserController@profile');

#URL generation
Route::get('basic/url-generation', 'UserController@exampleUrlGeneration');
Route::get('basic/url-generation/post/{post}', function () {
    //
})->name('post.show');
Route::get('basic/url-generation/post/{post}/comment/{comment}', function () {
    //
})->name('comment.show');

#VALIDATION
Route::get('basic/validation/post/create', 'PostController@create');
Route::post('basic/validation/post', 'PostController@store');
