<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    /**
     * Get the user that owns the phone.
     */
    public function post()
    {
        //One To Many (Inverse)
        return $this->belongsTo('App\Model\Post');
    }
}
