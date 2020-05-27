<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use NotificationChannels\Discord\Discord;

class UserDiscordSettingsController
{
    public function store(Request $request)
    {
        $userId = $request->input('discord_user_id');
        $channelId = app(Discord::class)->getPrivateChannel($userId);

        Auth::user()->update([
            'discord_user_id' => $userId,
            'discord_private_channel_id' => $channelId,
        ]);
    }
}
