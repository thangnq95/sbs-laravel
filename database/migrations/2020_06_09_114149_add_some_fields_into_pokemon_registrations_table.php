<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSomeFieldsIntoPokemonRegistrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pokemon_registrations', function (Blueprint $table) {
            $table->string('country', 50)->after('pokemon_name')->default(null);
            $table->integer('iv')->after('country')->default(null);
            $table->integer('cp')->after('iv')->default(null);
            $table->integer('level')->after('cp')->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
