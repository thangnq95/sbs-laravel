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
use Illuminate\Http\Request;
use App\Events\TaskEvent;

Auth::routes(['verify' => true]);

Route::get('/home', 'HomeController@index')->name('home');

//Query String
Route::group(['prefix' => 'api'],function (){
    Route::resource('posts', 'PostController');

    Route::resource('pokemon-registrations', 'PokemonRegistrationController');
    Route::post('pokemon-100-appear', 'PokemonRegistrationController@pokemonAppear');
});
