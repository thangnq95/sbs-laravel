<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use NotificationChannels\Discord\Discord;

class PokemonRegistration extends Model
{
    use Notifiable;

    protected $table = 'pokemon_registrations';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'no', 'discord_user_id', 'channel_id','channel_name'
    ];

    public function routeNotificationForDiscord()
    {
        return app(Discord::class)->getPrivateChannel($this->discord_user_id);
//        return $this->discord_user_id;
    }

}
