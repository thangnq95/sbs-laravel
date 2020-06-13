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
    protected $guarded = [];

    public function routeNotificationForDiscord()
    {
        return app(Discord::class)->getPrivateChannel($this->discord_user_id);
    }

}
