<?php

include __DIR__ . '/vendor/autoload.php';

use Discord\DiscordCommandClient;

$discord = new DiscordCommandClient([
//    'token' => env('DISCORD_TOKEN'),
    'token' => 'NzE0NjgyOTQwMjk4MzYyOTAw.XsyWfA.lFsBeqnp4nDxjyU8o1K6GvTfudQ',
]);

$discord->on('ready', function ($discord) {
    echo "Bot is ready.", PHP_EOL;
    // Listen for events here
    $discord->on('message', function ($message) {
        echo "Recieved a message from {$message->author->username}: {$message->content}", PHP_EOL;
        $messageDetect = explode(" ",$message->content);
        $prefix = $messageDetect[0];
        $actions = explode(":",$messageDetect[1]);
        echo $prefix;
        //Ivysaur
        if ($prefix == '!notify') {
            $messageReply = "Register $actions[0] on $actions[1] successful";
            $message->reply($messageReply);
        }
        if ($message->content == '!age') {
            $message->reply("I am one day old!");
        }
        //this will close the bot
        if ($message->content == '!exit') {
//            $discord->close();
        }

        //writes data in a txt file, the chat content, acts as a log
        date_default_timezone_set('Europe/Bucharest');
        $current_date = date('m.d.Y G:i:s');
        $file = 'storage/logs/discord.log';
        // Open the file to get existing content
        $current = file_get_contents($file);
        // Append a new person to the file
        $current .= $current_date . ' ' . $message->author->username . ': ' . $message->content . PHP_EOL;
        // Write the contents back to the file
        file_put_contents($file, $current);


    }); //end small function with content
}); //end main function ready

$discord->run();
?>

