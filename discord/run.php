<?php

include __DIR__ . '/vendor/autoload.php';
include 'const_variables.php';
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
        if ($channel_id == CHANNEL_LIST['test-notify'] || $channel_id == CHANNEL_LIST['beta-tester'] || $channel_id == CHANNEL_LIST['notify-bot']) {
            $messageDetect = explode(" ", $message->content);
            $prefix = $messageDetect[0];
            if ($prefix == '!notify') {
                $data = [];
                $discordUser = $message->author->user;
                $data['discord_user_id'] = $discordUser->id;
                $data['discord_username'] = $discordUser->username;

                switch ($messageDetect[1]) {
                    case 'help':
                        {
                            reply($message, MESSAGE_LIST['help_new']);
                        }
                        break;
                    case 'list':
                        {
                            $response = httpPostNonCurl(URL_LIST['list'], $data);
                            $response = json_decode($response, true);
                            reply($message, $response['message']);
                        }
                        break;
                    case 'off':
                        {
                            $data['value'] = isset($messageDetect[2]) ? $messageDetect[2] : "";
                            $response = httpPostNonCurl(URL_LIST['notify'], $data);
                            $response = json_decode($response, true);
                            reply($message, $response['message']);
                        }
                        break;
                    case 'iv100':
                        {
                            for ($i = 2; $i < count($messageDetect); $i++) {
                                $filterDataRaw = explode(":", $messageDetect[$i]);
                                if (count($filterDataRaw) == 2) {
                                    $data[$filterDataRaw[0]] = $filterDataRaw[1];
                                }
                            }
                            $data['channel_name'] = $messageDetect[1];
                            $data['channel_id'] = CHANNEL_LIST['iv100'] . "," . CHANNEL_LIST['iv100_lvl_30'];
                            sendRegisterRequest($message, $data);
                        }
                        break;
                    case 'iv90':
                    case 'ultra-rare':
                    case 'ditto-master':
                    case 'gen1':
                    case 'gen2':
                    case 'gen3':
                    case 'gen4':
                    case 'gen5':
                    case 'rank1':
                    case 'rank2-5':
                    case 'rank6-10':
                    case 'rank11-20':
                    case 'cp-2500':
                        {
                            for ($i = 2; $i < count($messageDetect); $i++) {
                                $filterDataRaw = explode(":", $messageDetect[$i]);
                                if (count($filterDataRaw) == 2) {
                                    $data[$filterDataRaw[0]] = $filterDataRaw[1];
                                }
                            }
                            $data['channel_name'] = $messageDetect[1];
                            $data['channel_id'] = CHANNEL_LIST[$messageDetect[1]];
                            sendRegisterRequest($message, $data);
                        }
                        break;
                    case 'all':
                        {
                            for ($i = 2; $i < count($messageDetect); $i++) {
                                $filterDataRaw = explode(":", $messageDetect[$i]);
                                if (count($filterDataRaw) == 2) {
                                    $data[$filterDataRaw[0]] = $filterDataRaw[1];
                                }
                            }
                            $data['channel_name'] = $messageDetect[1];
                            $data['channel_id'] = implode(",", CHANNEL_LIST);
                            sendRegisterRequest($message, $data);
                        }
                        break;
                    default:
                        {
                            reply($message, MESSAGE_LIST['channel_invalid']);
                        }
                        break;
                }
            }
        } else {
            $url = "";
            switch ($channel_id) {
                case CHANNEL_LIST['iv100']:
                case CHANNEL_LIST['iv100_lvl_30']:
                case CHANNEL_LIST['iv90']:
                case CHANNEL_LIST['ultra-rare']:
                case CHANNEL_LIST['ditto-master']:
                case CHANNEL_LIST['gen1']:
                case CHANNEL_LIST['gen2']:
                case CHANNEL_LIST['gen3']:
                case CHANNEL_LIST['gen4']:
                case CHANNEL_LIST['gen5']:
                case CHANNEL_LIST['cp-2500']:
                    {
                        $url = URL_LIST['appear'];
                    }
                    break;
                case CHANNEL_LIST['rank1']:
                case CHANNEL_LIST['rank2-5']:
                case CHANNEL_LIST['rank6-10']:
                case CHANNEL_LIST['rank11-20']:
                    {
                        $url = URL_LIST['pvp-appear'];
                    }
                    break;
                default:
                    break;
            }
            if ($url != "") {
                $data = [
                    'message' => $message->content,
                    'channel_id' => $channel_id
                ];
                httpPostNonCurl($url, $data);
            }
        }

    }); //end small function with content
}); //end main function ready

$discord->run();


?>
