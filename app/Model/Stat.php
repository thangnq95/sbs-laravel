<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Stat extends Model
{
      /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'hp', 'attack', 'defense', 'max_cp', 'max_buddy_cp',
    ];
    /**
     * Get the user that owns the phone.
     */
    public function pokemon()
    {
        return $this->belongsTo('App\Model\Pokemon');
    }
}
