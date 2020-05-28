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
            $pokemonRegistration = new PokemonRegistration();
            $pokemonRegistration->no = $pokemon->no;
            $pokemonRegistration->discord_user_id = $request->get('discord_user_id');
            $pokemonRegistration->channel_id = $request->get('channel_id');
            $pokemonRegistration->channel_name = $request->get('channel_name');
            $pokemonRegistration->save();
            $pokemonRegistration->notify(new PokemonRegistrationNotification($pokemonRegistration));
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
}
