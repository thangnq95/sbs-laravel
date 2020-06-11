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


//Query String
Route::group(['prefix' => 'api'],function (){
    Route::resource('pokemon-registrations', 'PokemonRegistrationController');
    Route::post('pokemon-registrations-off', 'PokemonRegistrationController@notifyOff');
    Route::post('pokemon-registrations-list', 'PokemonRegistrationController@notifyList');

    Route::post('pokemon-appear', 'PokemonRegistrationController@pokemonAppear');
    Route::post('pokemon-pvp-appear', 'PokemonRegistrationController@pokemonPvpAppear');
});
