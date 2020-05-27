<?php

include __DIR__ . '/vendor/autoload.php';

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
        $dataRegistation = explode(":", $messageDetect[1]);
        if ($prefix == '!notify') {
            //Define database connect
            $database = [
                'servername' => "127.0.0.1",
                'username' => "root",
                'password' => "mysql",
                'dbname' => "pokemon_go"
            ];
            $conn = openDBConnect($database);

            //Validate message
            if (isValidPokemon($conn, $dataRegistation[0])) {
                //Add registation
                $data['user_id'] = '1';
                $data['no'] = $dataRegistation[0];
                $data['channel'] = $dataRegistation[1];
                insertPokemonRegistation($conn, $data);
                $conn->close();
                //Reply
                $messageReply = "Notification: $dataRegistation[0] on $dataRegistation[1] is registered";
                $message->reply($messageReply);;
            } else {
                $message->reply("Didn't find pokemon: $dataRegistation[0]");
            }
        }
    }); //end small function with content
}); //end main function ready

$discord->run();


function openDBConnect($database)
{
    // Create connection
    $conn = new mysqli($database['servername'], $database['username'], $database['password'], $database['dbname']);
    // Check connection
    if ($conn->connect_error) {
        return false;
    }
    return $conn;
}

function insertPokemonRegistation($conn, $data)
{
    $name = $data['user_id'];
    $no = $data['no'];
    $channel = $data['channel'];
    $now = date('Y-m-d h:i:sa');
    $sql = "INSERT INTO `pokemon_registations` (`user_id`, `no`, `channel`,`created_at`,`updated_at`) VALUES ('$name', '$no', '$channel','$now','$now')";

    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
        return true;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
        return false;
    }
}

function isValidPokemon($conn, $data)
{
    $sql = "SELECT `no`,`name` FROM `pokemons` WHERE";
    //This is name
    if (preg_match("/^\d+$/", $data)) {
        $sql .= " `no` = '$data'";
    } else {
        $sql .= " `name` = '$data'";
    }
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        return $data;
    } else {
        return false;
    }
}

?>
