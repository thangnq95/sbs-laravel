<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{

    /**
     * Get all of the posts for the country.
     */
    public function posts()
    {
//        return $this->hasManyThrough('App\Model\Post', 'App\User');
        return $this->hasManyThrough(
            'App\Model\Post',
            'App\User',
            'country_id', // Foreign key on users table...
            'user_id', // Foreign key on posts table...
            'id', // Local key on countries table...
            'id' // Local key on users table...
        );
    }
}
