<?php


//Overwrite reply function //$model->reply($message)
function reply($message, $text)
{
    return $message->channel->sendMessage("{$text}\n{$message->author}");
}

//Overwrite reply function //$model->reply($message)
function sendRegisterRequest($message, $data)
{
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
    }
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
