<?php
use App\Http\Middleware\CheckAge;
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

Route::get('foo', function () {
    return 'Hello World';
})->middleware('age');

Route::get('foo1', function () {
    return 'Hello World 1';
})->middleware(CheckAge::class);

Route::put('post/{id}', function ($id) {
    //
})->middleware('role:editor');
