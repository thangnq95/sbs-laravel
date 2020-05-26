<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Pokemon extends Model
{
    protected $table = 'pokemons';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'no', 'pic', 'name', 'types', 'stats', 'fast_attacks', 'special_attacks',
    ];

    /**
     * Get the phone record associated with the user.
     */
    public function stats()
    {
        return $this->hasOne('App\Model\Stat');
    }


}
