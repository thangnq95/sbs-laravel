<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePokemonCoordinatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pokemon_coordinates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('no',5);
            $table->string('cp',5);
            $table->string('hp');
            $table->string('attack');
            $table->string('defense');
            $table->bigInteger('seconds_dsp');
            $table->string('lat');
            $table->string('long');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pokemon_coordinates');
    }
}
