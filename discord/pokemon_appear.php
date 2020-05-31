<?php
function pokemonAppear($message)
{
    var_dump($message->channel_id);
    switch ($message->channel_id) {
        case IV100_ID:
        case IV100_LVL30_ID:
            {
                var_dump("AAAAAAA");
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
