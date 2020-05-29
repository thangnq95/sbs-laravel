<?php

include __DIR__ . '/vendor/autoload.php';
include 'const_variables.php';

use Discord\DiscordCommandClient;

$discord = new DiscordCommandClient([
    'token' => DISCORD_BOT_TOKEN,
    'discordOptions' => [
        'loggerLevel' => 'INFO',
        'disabledEvents' => ['PRESENCE_UPDATE'],
    ],
]);

$discord->on('ready', function ($discord) {
    echo "Bot is ready.", PHP_EOL;
    // Listen for events here
    $discord->on('message', function ($message) {
        //Check correct channel to listen
        if ($message->channel_id == CHANNEL_REGISTER_ID) {
            $data = [];
            $messageDetect = explode(" ", $message->content);
            $prefix = $messageDetect[0];
//            $dataRegistration = explode(":", $messageDetect[1]);
            if ($prefix == '!notify') {
                switch ($messageDetect[1]) {
                    case 'help':
                        {
                            reply($message, HELP_MESSAGE);
                            return;
                        }
                        break;
                    case 'iv100':
                        {
                            if (isset($messageDetect[2])) {
                                $data['pokemon_name'] = $messageDetect[2];
                            }
                            $data['channel_name'] = 'iv100';
                            $data['channel_id'] = implode(",", [IV100_ID, IV100_LVL30_ID]);
                        }
                        break;
                    case 'rank1':
                        {
                            if (isset($messageDetect[2])) {
                                $data['pokemon_name'] = $messageDetect[2];
                            }
                            $data['channel_name'] = 'rank1';
                            $data['channel_id'] = PVP_RANK1_ID;
                        }
                        break;
                    default:
                        break;
                }
                $discordUser = $message->author->user;
                $data['discord_user_id'] = $discordUser->id;
                if (isset($data['channel_name'])) {
                    $response = httpPostNonCurl(REGISTER_URL, $data);
                    $response = json_decode($response, true);

                    //Validate message
                    if ($response['success']) {
                        $messageReply = $data['channel_name'] . " is registered";
                        reply($message, $messageReply);
                    } else {
                        reply($message, $response['message']);
                    }
                } else {
                    reply($message, "Channel is invalid");
                }
            }
        } else {
            switch ($message->channel_id) {
                case IV100_ID:
                case IV100_LVL30_ID:
                {
                    $data = $matches = [];
                    preg_match('/\*\*\*\*(.*)\*\*\*\*/', $message->content, $matches);
                    $pokemonName = $matches[1];
                    $url = HOST . "/api/pokemon-100-appear";
                    $data = [
                        'pokemon_name' => $pokemonName,
                        'message' => $message->content
                    ];
                    httpPostNonCurl($url, $data);
                }
                case PVP_RANK1_ID:
                    {
                        var_dump($message->content);
                        die();
                        $data = $matches = [];
                        preg_match('/\*\*\*\*(.*)\*\*\*\*/', $message->content, $matches);
                        $pokemonName = $matches[1];
                        $url = HOST . "/api/pokemon-rank1-appear";
                        $data = [
                            'pokemon_name' => $pokemonName,
                            'message' => $message->content,
                        ];
                        httpPostNonCurl($url, $data);
                    }
                    break;
                default:
                    break;
            }

        }

    }); //end small function with content
}); //end main function ready

$discord->run();


//Overwrite reply function //$model->reply($message)
function reply($message, $text)
{
    return $message->channel->sendMessage("{$text}\n{$message->author}");
}

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
