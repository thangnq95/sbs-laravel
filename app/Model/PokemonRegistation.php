<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PokemonRegistation extends Model
{
    protected $table = 'pokemon_registations';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'no', 'user_id', 'no', 'channel'
    ];


}
