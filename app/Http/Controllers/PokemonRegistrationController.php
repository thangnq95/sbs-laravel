<?php

namespace App\Http\Controllers;

use App\Model\Country;
use App\Model\Pokemon;
use App\Model\PokemonRegistration;
use App\Notifications\PokemonRegistrationNotification;
use Illuminate\Http\Request;

const IV100_ID = '701624383579357265,701625607133462528';
const RANK1_ID = '705080025413845035';

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
        //Register for
        //"!notify iv100 pokemon_name(DM pokemon_name IV100 feed)\n" .
        //"!notify rank1 pokemon_name(DM pokemon_name pvp rank 1)\n" .
        if ($request->has('pokemon_name')) {
            $pokemonName = $request->get('pokemon_name');
            $pokemon = Pokemon::whereRaw("UPPER(`name`) LIKE '%" . strtoupper($pokemonName) . "%'")->first();
            if ($pokemon) {
                $pokemonRegistration = PokemonRegistration::firstOrNew(
                    [
                        'no' => $pokemon->no,
                        'discord_user_id' => $request->get('discord_user_id'),
                        'discord_username' => $request->get('discord_username'),
                        'channel_id' => $request->get('channel_id')
                    ]
                );
                $pokemonRegistration->name = $pokemon->name;
                $pokemonRegistration->channel_name = $request->get('channel_name');
            } else {
                return json_encode([
                    'success' => false,
                    'message' => "Pokemon can't find."
                ]);
            }
        } else {
            //"!notify iv100 (DM all IV100 feed)\n" .
            //"!notify rank1 (DM all pvp rank 1)\n" .
            $pokemonRegistration = PokemonRegistration::firstOrNew(
                [
                    'discord_user_id' => $request->get('discord_user_id'),
                    'channel_name' => $request->get('channel_name'),
                    'name' => null,
                ]
            );
            if ($request->has('filter')) {
                $filter = json_decode($request->get('filter'), true);
                if (isset($filter['country'])) {
                    $country = Country::where('country_name', $filter['country'])->first();
                    if ($country == null) {
                        return json_encode([
                            'success' => false,
                            'data' => $filter['country'] . " can't find."
                        ]);
                    }
                }

            }
            $pokemonRegistration->channel_id = $request->get('channel_id');
        }
        $pokemonRegistration->save();
        $pokemonRegistration->notify(new PokemonRegistrationNotification($pokemonRegistration, $request->get('channel_name') . " was registered!"));
        return json_encode([
            'success' => true,
            'data' => $pokemonRegistration
        ]);

    }

    /**
     * Store a new blog post.
     *
     * @param Request $request
     * @return false|string
     */
    public function notifyOff(Request $request)
    {
        $discord_user_id = $request->get('discord_user_id');
//        $rs = PokemonRegistration::where('discord_user_id', $discord_user_id)
//            ->update(['status' => 0]);
        $rs = PokemonRegistration::where('discord_user_id', $discord_user_id)->delete();
        return json_encode([
            'success' => true,
            'message' => "Notification is off."
        ]);
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

        $pokemonRegistrations = PokemonRegistration::where(['channel_id' => RANK1_ID, 'status' => 1])
            ->orWhere([
                ['name', 'like', "%" . $pokemonName . "%"],
                ['channel_id', IV100_ID],
                ['status', 1],
            ])->groupby('discord_user_id')->get();
        preg_match("/DSP.{13}/", $messageArray[1], $dsp);
        $message = "**A $pokemonName spawned in channel_id**\n";
        $message .= "**$pokemonName**\n";
        $message .= $dsp[0] . "\n";
        $message .= "**IV** 100 (15/15/15) ** CP:** $cp[0] ** Level:** " . str_replace("*", "", $dataCountry[1]);
        $message .= "**Country:** $dataCountry[2]";
        $message .= "```" . str_replace("✰", "", $dataCountry[3]) . "``` \n";

        foreach ($pokemonRegistrations as $registration) {
            $registration->notify(new PokemonRegistrationNotification($registration, $message));
        }
        return json_encode(['success' => true]);
    }

    /**
     * Store a new blog post.
     *
     * @param Request $request
     * @return false|string
     */
    public function pokemonPvpAppear(Request $request)
    {
        $messageRaw = $request->get('message');
        $messageArray = explode(' ', str_replace("\n", " ", $messageRaw));
        $messageArray = array_values(array_filter($messageArray, function ($v) {
            return $v != "";
        }, 0));
        $pokemonRegistrations = PokemonRegistration::where(['channel_id' => RANK1_ID, 'status' => 1])
            ->orWhere([
                ['name', 'like', "%" . $messageArray[0] . "%"],
                ['channel_id', RANK1_ID],
                ['status', 1]
            ])->groupby('discord_user_id')->get();

        preg_match("/<:MS:705082254162002091>:.*/", $messageRaw, $move);
        preg_match("/<:DSP:703419665132814396>:.*/", $messageRaw, $dps);
        preg_match("/sec.*\n.*/", $messageRaw, $country);
        $count = count($messageArray);
        $message = "$messageArray[0] $messageArray[1] $messageArray[2] \n";
        $message .= "Rank $messageArray[4] $messageArray[5] $messageArray[6] $messageArray[8] \n";
        $message .= "Stardust: $messageArray[11]  Candy: $messageArray[13] \n";
        $message .= "🅟🅞🅚🅔🅗🅤🅑 🅟🅥🅟 \n";
        $message .= "CP: $messageArray[17] LVL: $messageArray[19]  \n";
        $message .= "IV: $messageArray[21] - $messageArray[23]  \n";
        $message .= "Moves: " . str_replace("<:MS:705082254162002091>: ", "", $move[0]) . "\n";
        $message .= "DSP: ".str_replace("<:DSP:703419665132814396>: ", "", $dps[0])."\n";
        $message .=  str_replace("sec\n", "", $country[0]). " \n";;
        $message .= $messageArray[$count - 2] . $messageArray[$count - 1] . " \n";
        $message .= "*************\n\n";

        foreach ($pokemonRegistrations as $registration) {
            $registration->notify(new PokemonRegistrationNotification($registration, $message));
        }
        return json_encode(['success' => true]);
    }

}
