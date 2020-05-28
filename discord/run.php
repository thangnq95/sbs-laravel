<?php

include __DIR__ . '/vendor/autoload.php';

const HOST = 'http://127.0.0.1:8000';
const IV100 = '701624383579357265';

use Discord\DiscordCommandClient;

$discord = new DiscordCommandClient([
//    'token' => env('DISCORD_TOKEN'),
    'token' => 'NzE0NjgyOTQwMjk4MzYyOTAw.Xs4tBQ.QXvCUpoxFZ2heZimEh6HfrrCHE0',
    'discordOptions' => [
        'loggerLevel' => 'INFO',
        'disabledEvents' => ['PRESENCE_UPDATE'],
    ],
]);

$discord->on('ready', function ($discord) {
    echo "Bot is ready.", PHP_EOL;
    // Listen for events here
    $discord->on('message', function ($message) {
        if ($message->channel->name == 'test-notify') {
            $data = [];
            $messageDetect = explode(" ", $message->content);
            $prefix = $messageDetect[0];
            $dataRegistration = explode(":", $messageDetect[1]);
            if ($prefix == '!notify') {
                $url = HOST . "/api/pokemon-registrations";
                //Add registation
                $discordUser = $message->author->user;
                $discordChannel = $message->channel;
                $data['discord_user_id'] = $discordUser->id;
                $data['name_or_no'] = $dataRegistration[0];
                $data['channel_id'] = $discordChannel->id;
                $data['channel_name'] = $discordChannel->name;
                $response = httpPostNonCurl($url, $data);
                $response = json_decode($response, true);
                //Validate message
                if ($response['success']) {
                    $messageReply = "Notification: $dataRegistration[0] on $dataRegistration[1] is registered";
                    $message->reply($messageReply);
                } else {
                    $message->reply($response['message']);
                }
            }
        } elseif ($message->channel_id == IV100) {
            $data = $matches = [];
            preg_match('/\*\*\*\*(.*)\*\*\*\*/', $message->content, $matches);
            $pokemonName = $matches[1];
            $url = HOST . "/api/pokemon-100-appear";
            $data = [
                'pokemon_name' => $pokemonName,
                'message' => $message->content,
            ];
            $response = httpPostNonCurl($url, $data);
        }

    }); //end small function with content
}); //end main function ready

$discord->run();

//Non curl Method
function httpPostNonCurl($url, $data)
{
    $options = array(
        'http' => array(
            'header' => "Content-type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($data)
        )
    );
    $context = stream_context_create($options);
    return file_get_contents($url, false, $context);
}


?>
