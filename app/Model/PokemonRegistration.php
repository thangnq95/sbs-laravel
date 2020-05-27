<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PokemonRegistration extends Model
{
    protected $table = 'pokemon_registrations';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'no', 'user_id', 'no', 'channel'
    ];


}
