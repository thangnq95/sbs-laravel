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
            $table->string('no',5)->default(null);
            $table->string('pokemon_name',50)->after('channel_id')->default(null);
            $table->string('discord_user_id');
            $table->string('discord_user_name')->after('discord_user_id')->default(null);
            $table->string('channel_id');
            $table->string('channel_name',50);
            $table->string('country', 50)->after('pokemon_name')->default("");
            $table->integer('iv')->after('country')->default(0);
            $table->integer('cp')->after('iv')->default(0);
            $table->integer('level')->after('cp')->default(0);
            $table->boolean('status')->after('channel_name')->default(1);

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
