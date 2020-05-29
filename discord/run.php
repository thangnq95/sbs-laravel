<?php

include __DIR__ . '/vendor/autoload.php';

const HOST = 'http://127.0.0.1:8000';

const IV100_ID = '701624383579357265';
const IV100_NAME = '100iv';

const IV100_LVL30_ID = '701625607133462528';
const IV100_LVL30_NAME = '100iv-lvl30';

const PVP_RANK1_ID = '705080025413845035';
const PVP_RANK1_NAME = 'pvp-rank1-1';

const CHANNEL_REGISTER_ID = '715443208112308244';

const REGISTER_URL = HOST . "/api/pokemon-registrations";

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
        //Check correct channel to listen
        if ($message->channel_id == CHANNEL_REGISTER_ID) {
            $data = [];
            $messageDetect = explode(" ", $message->content);
            $prefix = $messageDetect[0];
            $dataRegistration = explode(":", $messageDetect[1]);
            if ($prefix == '!notify') {
                $discordUser = $message->author->user;
                $data['discord_user_id'] = $discordUser->id;
                $data['name_or_no'] = $dataRegistration[0];
                switch ($dataRegistration[1]) {
                    case IV100_NAME:
                        {
                            $data['channel_id'] = IV100_ID;
                            $data['channel_name'] = IV100_NAME;
                        }
                        break;
                    case IV100_LVL30_NAME:
                        {
                            $data['channel_id'] = IV100_LVL30_ID;
                            $data['channel_name'] = IV100_LVL30_NAME;
                        }
                        break;
                    case PVP_RANK1_NAME:
                        {
                            $data['channel_id'] = PVP_RANK1_ID;
                            $data['channel_name'] = PVP_RANK1_NAME;
                        }
                        break;
                    default:
                        break;
                }
                $response = httpPostNonCurl(REGISTER_URL, $data);
                $response = json_decode($response, true);
                //Validate message
                if ($response['success']) {
                    $messageReply = "Notification: $dataRegistration[0] on $dataRegistration[1] is registered";
                    $message->reply($messageReply);
                } else {
                    $message->reply($response['message']);
                }
            }
        } else {
            switch ($message->channel_id) {
                case IV100_ID:
                    {
                        $data = $matches = [];
                        preg_match('/\*\*\*\*(.*)\*\*\*\*/', $message->content, $matches);
                        $pokemonName = $matches[1];
                        $url = HOST . "/api/pokemon-100-appear";
                        $data = [
                            'pokemon_name' => $pokemonName,
                            'message' => $message->content,
                        ];
                        httpPostNonCurl($url, $data);
                    }
                    break;
                case IV100_LVL30_ID:
                    {
                        $data = $matches = [];
                        preg_match('/\*\*\*\*(.*)\*\*\*\*/', $message->content, $matches);
                        $pokemonName = $matches[1];
                        $url = HOST . "/api/pokemon-100-lvl30-appear";
                        $data = [
                            'pokemon_name' => $pokemonName,
                            'message' => $message->content,
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
