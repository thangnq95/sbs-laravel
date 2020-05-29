<?php

namespace App\Http\Controllers;

use App\Model\Pokemon;
use App\Model\PokemonRegistration;
use App\Notifications\PokemonRegistrationNotification;
use Illuminate\Http\Request;
use NotificationChannels\Discord\Discord;

const IV100_ID = '701624383579357265';
const IV100_NAME = '100iv';

const IV100_LVL30_ID = '701625607133462528';
const IV100_LVL30_NAME = '100iv-lvl30';

const PVP_RANK1_ID = '705080025413845035';
const PVP_RANK1_NAME = 'pvp-rank1-1';

class PokemonRegistrationController extends Controller
{
    /**
     * Store a new blog post.
     *
     * @param Request $request
     * @return false|string
     */
    public function store(Request $request)
    {
        $nameOrNo = $request->get('name_or_no');
        if (preg_match("/^\d+$/", $nameOrNo)) {
            $column = 'no';
        } else {
            $column = 'name';
        }
        $pokemon = Pokemon::whereRaw("UPPER(`$column`) LIKE '%" . strtoupper($nameOrNo) . "%'")->first();
        if ($pokemon) {
            $pokemonRegistration = PokemonRegistration::firstOrNew(
                [
                    'no' => $pokemon->no,
                    'discord_user_id' => $request->get('discord_user_id'),
                    'channel_id' => $request->get('channel_id')
                ]
            );
            $pokemonRegistration->name = $pokemon->name;
            $pokemonRegistration->channel_name = $request->get('channel_name');
            $pokemonRegistration->save();
            $pokemonRegistration->notify(new PokemonRegistrationNotification($pokemonRegistration, "Message test!"));
            return json_encode([
                'success' => true,
                'data' => $pokemonRegistration
            ]);
        } else {
            return json_encode([
                'success' => false,
                'message' => "Pokemon can't find."
            ]);
        }
    }

    /**
     * Store a new blog post.
     *
     * @param Request $request
     * @return false|string
     */
    public function pokemonAppear(Request $request)
    {
        $pokemonName = $request->get('pokemon_name');
        $messageRaw = $request->get('message');
        $messageArray = explode(" **", $messageRaw);
        preg_match("/\s\d{0,4}/", $messageArray[3], $cp);
        $dataCountry = explode("> ", $messageArray[4]);
        $path = $request->path();
        if ($path == "api/pokemon-100-appear") {
            $PokemonRegistrations = PokemonRegistration::where(['name'=> $pokemonName, 'channel_id' => IV100_ID])->get();
        } elseif ($path == "pokemon-100-lvl30-appear") {
            $PokemonRegistrations = PokemonRegistration::where(['name'=> $pokemonName, 'channel_id' => IV100_LVL30_ID])->get();
        }
        preg_match("/DSP.{13}/", $messageArray[1], $dsp);
        $message = "**A $pokemonName spawned in $PokemonRegistrations->channel_id**\n";
        $message .= "**$pokemonName**\n";
        $message .= $dsp[0]."\n";
        $message .= "**IV** 100 (15/15/15) ** CP:** $cp[0] ** Level:** " . str_replace("*", "", $dataCountry[1]);
        $message .= "**Country:** $dataCountry[2]";
        $message .= "```" . str_replace("✰", "", $dataCountry[3]) . "``` \n";

        foreach ($PokemonRegistrations as $registration) {
            $registration->notify(new PokemonRegistrationNotification($registration, $message));
        }
        return json_encode(['success' => true]);
    }

}
