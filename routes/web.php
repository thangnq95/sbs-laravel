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

Route::get('export', 'PokemonController@export')->name('export');
Route::get('importExportView', 'PokemonController@importExportView');
Route::post('import', 'PokemonController@import')->name('import');

//Discord
Route::get('discord-run', 'DiscordBotController@run')->name('discord-run');
Route::get('discord-stop', 'DiscordBotController@stop')->name('discord-stop');

//Test send message direct
