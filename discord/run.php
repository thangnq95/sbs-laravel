<?php

include __DIR__ . '/vendor/autoload.php';

const HOST = 'http://127.0.0.1:8000';

use Discord\DiscordCommandClient;

$discord = new DiscordCommandClient([
//    'token' => env('DISCORD_TOKEN'),
    'token' => 'NzE0NjgyOTQwMjk4MzYyOTAw.Xs4tBQ.QXvCUpoxFZ2heZimEh6HfrrCHE0',
]);

$discord->on('ready', function ($discord) {
    echo "Bot is ready.", PHP_EOL;
    // Listen for events here
    $discord->on('message', function ($message) {
        $messageDetect = explode(" ", $message->content);
        $prefix = $messageDetect[0];
        $dataRegistration = explode(":", $messageDetect[1]);
        if ($prefix == '!notify') {
            $url = HOST."/api/pokemon-registrations";
            //Add registation
            $discordUser = $message->author->user;
            $discordChannel = $message->channel;
            $data['user_id'] = $discordUser->id;
            $data['name_or_no'] = $dataRegistration[0];
            $data['channel_id'] = $discordChannel->id;
            $data['channel_name'] = $discordChannel->name;
            $response = httpPostNonCurl($url,$data);
            $response = json_decode($response,true);
             var_dump($response);
            //Validate message
            if ($response['success']) {
                $messageReply = "Notification: $dataRegistration[0] on $dataRegistration[1] is registered";
                $message->reply($messageReply);
            } else {
                $message->reply($response['message']);
            }
        }
    }); //end small function with content
}); //end main function ready

$discord->run();

//using php curl (sudo apt-get install php-curl)
function httpPost($url, $data){
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
}

//Non curl Method
function httpPostNonCurl($url, $data){
    $options = array(
        'http' => array(
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data)
        )
    );
    $context  = stream_context_create($options);
    return file_get_contents($url, false, $context);
}


?>
