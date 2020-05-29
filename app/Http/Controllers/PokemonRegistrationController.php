<?php

namespace App\Http\Controllers;

use App\Model\Pokemon;
use App\Model\PokemonRegistration;
use App\Notifications\PokemonRegistrationNotification;
use Illuminate\Http\Request;
use NotificationChannels\Discord\Discord;

const IV100_ID = '701624383579357265,701625607133462528';

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
        if ($request->has('pokemon_name')) {
            $pokemonName = $request->get('pokemon_name');
            $pokemon = Pokemon::whereRaw("UPPER(`name`) LIKE '%" . strtoupper($pokemonName) . "%'")->first();
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
            } else {
                return json_encode([
                    'success' => false,
                    'message' => "Pokemon can't find."
                ]);
            }
        } else {
            $pokemonRegistration = PokemonRegistration::firstOrNew(
                [
                    'discord_user_id' => $request->get('discord_user_id'),
                    'channel_name' => $request->get('channel_name'),
                    'name' => null,
                ]
            );
            $pokemonRegistration->channel_id = $request->get('channel_id');
        }
        $pokemonRegistration->save();
        $pokemonRegistration->notify(new PokemonRegistrationNotification($pokemonRegistration, "Message test!"));
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
    public function pokemonAppear(Request $request)
    {
        $pokemonName = $request->get('pokemon_name');
        $channelId = $request->get('channel_id');
        $messageRaw = $request->get('message');
        $messageArray = explode(" **", $messageRaw);
        preg_match("/\s\d{0,4}/", $messageArray[3], $cp);
        $dataCountry = explode("> ", $messageArray[4]);
        //Todo distinct data
        $pokemonRegistrations = PokemonRegistration::where(['channel_id' => IV100_ID])
            ->orWhere([
                ['name', 'like', "%" . $pokemonName . "%"],
                ['channel_id', '<>', IV100_ID]
            ])->get();
        var_dump($pokemonRegistrations);
        die();
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

}
