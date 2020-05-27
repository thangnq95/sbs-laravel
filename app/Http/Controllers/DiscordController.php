<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use NotificationChannels\Discord\Discord;
use App\Notifications\DiscordNotification;

class DiscordController extends Controller
{
    public function send(Request $request)
    {
//        $userId = $request->input('discord_user_id');
        $userId = $request->input('discord_user_id');
        $channelId = app(Discord::class)->getPrivateChannel($userId);
        $newNotify = new DiscordNotification();
        $newNotify->toDiscord("AAAAAAAA");

    }
}
