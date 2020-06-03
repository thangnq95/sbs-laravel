<?php

include __DIR__ . '/vendor/autoload.php';
include 'const_variables.php';
include 'registration.php';
include 'common_function.php';

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
        $channel_id = $message->channel_id;
        if ($channel_id == CHANNEL_REGISTER_ID) {
            $messageDetect = explode(" ", $message->content);
            $prefix = $messageDetect[0];
            if ($prefix == '!notify') {
                $data = [];
                $discordUser = $message->author->user;
                $data['discord_user_id'] = $discordUser->id;
                switch ($messageDetect[1]) {
                    case 'help':
                        {
                            reply($message, HELP_MESSAGE);
                        }
                        break;
                    case 'off':
                        {
                            $response = httpPostNonCurl(NOTIFY_OFF_URL, $data);
                            $response = json_decode($response, true);
                            reply($message, $response['message']);
                        }
                        break;
                    case 'iv100':
                        {
                            if (isset($messageDetect[2])) {
                                $data['pokemon_name'] = $messageDetect[2];
                            }
                            $data['channel_name'] = 'iv100';
                            $data['channel_id'] = implode(",", [IV100_ID, IV100_LVL30_ID]);
                            sendRegisterRequest($message, $data);
                        }
                        break;
                    case 'rank1':
                        {
                            if (isset($messageDetect[2])) {
                                $data['pokemon_name'] = $messageDetect[2];
                            }
                            $data['channel_name'] = 'rank1';
                            $data['channel_id'] = PVP_RANK1_ID;
                            sendRegisterRequest($message, $data);
                        }
                        break;
                    case 'iv':
                    case 'cp':
                    case 'level':
                    case 'country':
                        {
//                            //$messageDetect[2] = keyword:channel
//                            $tailMessage = explode(":", $messageDetect[2]);
//                            if (isset($messageDetect[2])) {
//                                $data['filter'] = json_encode([$messageDetect[1] => $tailMessage[0]]);//keyword
//                            }
//                            //!notify country (keyword):channel
//                            $data['channel_name'] = $tailMessage[1];//channel
//                            $data['channel_id'] = "channel_id_test";//Todo get channel_id
//                            sendRegisterRequest($message, $data);
                        }
                        break;
                    default:
                        break;
                }
            }
        } else {
            switch ($channel_id) {
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
                    break;
                case PVP_RANK1_ID:
                    {

                    }
                    break;
                default:
                    break;
            }
        }

    }); //end small function with content
}); //end main function ready

$discord->run();


?>
