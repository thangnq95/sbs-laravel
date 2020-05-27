<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\Discord\DiscordChannel;
use NotificationChannels\Discord\DiscordMessage;
use Discord\Parts\Guild\Guild;

class DiscordNotification extends Notification
{
    use Queueable;

    public $challenger;

    public function __construct(Guild $challenger)
    {
        $this->challenger = $challenger;
    }

    public function via($notifiable)
    {
        return [DiscordChannel::class];
    }

    public function toDiscord($notifiable)
    {
        return DiscordMessage::create("You have been challenged to a game of ** by **{$this->challenger->name}**!");
    }
}
