<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Model\PokemonRegistration;
use NotificationChannels\Discord\DiscordChannel;
use NotificationChannels\Discord\DiscordMessage;

class PokemonRegistrationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $pokemonRegistration;
    public $message;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(PokemonRegistration $pokemonRegistration, $message)
    {
        $this->pokemonRegistration = $pokemonRegistration;
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [DiscordChannel::class];
    }

    public function toDiscord($notifiable)
    {
        return DiscordMessage::create($this->message);
    }

}
