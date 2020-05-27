<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    /**
     * Get the user's history.
     */
    public function userHistory()
    {
//        return $this->hasOneThrough('App\Model\History', 'App\User');
        return $this->hasOneThrough(
            'App\Model\History',
            'App\User',
            'supplier_id', // Foreign key on users table...
            'user_id', // Foreign key on history table...
            'id', // Local key on suppliers table...
            'id' // Local key on users table...
        );
    }
}
