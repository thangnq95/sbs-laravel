<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePokemonRegistrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pokemon_registrations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('no',5)->nullable();
            $table->string('pokemon_name',50)->default("");
            $table->string('discord_user_id');
            $table->string('discord_user_name')->nullable();
            $table->string('channel_id');
            $table->string('channel_name',50);
            $table->string('country', 50)->default("");
            $table->integer('iv')->default(0);
            $table->integer('cp')->default(0);
            $table->integer('level')->default(0);
            $table->boolean('status')->default(1);

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
        Schema::dropIfExists('pokemon_registations');
    }
}
