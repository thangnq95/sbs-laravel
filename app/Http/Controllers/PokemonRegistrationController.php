<?php

namespace App\Http\Controllers;

use App\Model\Pokemon;
use App\Model\PokemonRegistration;
use App\Notifications\PokemonRegistrationNotification;
use Illuminate\Http\Request;
use NotificationChannels\Discord\Discord;

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
        $pokemon = Pokemon::where($column, $nameOrNo)->first();
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
        $message = $request->get('message');
        $PokemonRegistrations = PokemonRegistration::where('name', $pokemonName)->get();
        foreach ($PokemonRegistrations as $registration) {
            $registration->notify(new PokemonRegistrationNotification($registration, $message));
        }
        return json_encode(['success' => true]);
    }
}
