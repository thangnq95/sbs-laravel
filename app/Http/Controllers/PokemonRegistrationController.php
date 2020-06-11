<?php

namespace App\Http\Controllers;

use App\Model\Country;
use App\Model\Pokemon;
use App\Model\PokemonRegistration;
use App\Notifications\PokemonRegistrationNotification;
use Illuminate\Http\Request;

class PokemonRegistrationController extends Controller
{
    /**
     * Store a new pokemon registration.
     *
     * @param Request $request
     * @return false|object
     */
    public function store(Request $request)
    {
        $channelName = $request->get('channel_name');
        $channelId = $request->get('channel_id');
        $discordUserId = $request->get('discord_user_id');
        $error = false;
        $errorField = [];
        if ($request->has('pokemon_name')) {
            $pokemonName = $request->get('pokemon_name');
            $pokemon = Pokemon::whereRaw("UPPER(`name`) LIKE '%" . strtoupper($pokemonName) . "%'")->first();
            if (!$pokemon) {
                $errorField[] = "pokemon_name";
                $error = true;
            }
        }
        if ($request->has('country')) {
            $countryName = $request->get('country');
            $country = Country::whereRaw("UPPER(`name`) LIKE '%" . strtoupper($countryName) . "%' OR UPPER(`code`) LIKE '%" . strtoupper($countryName) . "%' ")->first();
            if (!$country) {
                $errorField[] = "country";
                $error = true;
            }
        }
        if ($error) {
            return json_encode([
                'success' => false,
                'message' => implode(",", $errorField) . " invalid."
            ]);
        } else {
            $pokemonRegistration = PokemonRegistration::firstOrNew(
                [
                    'discord_user_id' => $discordUserId,
                    'channel_id' => $channelId
                ]
            );
            $pokemonRegistration->channel_name = $channelName;
            $pokemonRegistration->no = isset($pokemon->no) ? $pokemon->no : null;
            $pokemonRegistration->country = isset($country->name) ? $country->name : "";
            $pokemonRegistration->iv = ($request->has('iv')) ? $request->get('iv') : 0;
            $pokemonRegistration->cp = ($request->has('cp')) ? $request->get('cp') : 0;
            $pokemonRegistration->level = ($request->has('level')) ? $request->get('level') : 0;

            $messageReply = $channelName . " was registered!";

            $pokemonRegistration->save();
            $pokemonRegistration->notify(new PokemonRegistrationNotification($pokemonRegistration, $messageReply));
            return json_encode([
                'success' => true,
                'message' => $messageReply,
                'data' => $pokemonRegistration
            ]);
        }

    }

    /**
     * Turn off notify for user.
     *
     * @param Request $request
     * @return false|object
     */
    public function notifyOff(Request $request)
    {
        $discord_user_id = $request->get('discord_user_id');
        $rs = PokemonRegistration::where('discord_user_id', $discord_user_id)->delete();
        return json_encode([
            'success' => true,
            'message' => "Notification is off."
        ]);
    }

    /**
     * Send notify when rank1 appear in wild.
     *
     * @param Request $request
     * @return false|object
     */
    public function pokemonAppear(Request $request)
    {
        $messageRaw = $request->get('message');
        $matches = [];
        preg_match('/\*\*\*\*(.*)\*\*\*\*/', $messageRaw, $matches);
        $pokemonName = $matches[1];
        $channelId = $request->get('channel_id');
        $messageArray = explode(" **", $messageRaw);
        preg_match("/\s\d{0,4}/", $messageArray[3], $cpData);
        $dataCountry = explode("> ", $messageArray[4]);

        preg_match("/DSP.{13}/", $messageArray[1], $dsp);
        $cp = $cpData[0];
        $level = str_replace("*", "", $dataCountry[1]);
        $country = $dataCountry[2];

        $message = "**A $pokemonName spawned!!**\n";
        $message .= "**$pokemonName**\n";
        $message .= $dsp[0] . "\n";
        $message .= "**IV** 100 (15/15/15) ** CP:** $cp ** Level:** " . $level;
        $message .= "**Country:** $country";
        $message .= "```" . str_replace("✰", "", $dataCountry[3]) . "``` \n";

        $pokemonRegistrations = $this->getListRegistration($channelId, $pokemonName, 100, $cp, $level, $country);
        foreach ($pokemonRegistrations as $registration) {
            $registration->notify(new PokemonRegistrationNotification($registration, $message));
        }
        return json_encode(['success' => true]);
    }

    /**
     * Send notify when pvp2 appear in wild.
     *
     * @group  Pokemon appear
     * @param Request $request
     * @return false|object
     */
    public function pokemonPvpAppear(Request $request)
    {
        $messageRaw = $request->get('message');
        preg_replace("/\[.*]/", "", $messageRaw);
        preg_replace("/<@&680277216046874636>/", "", $messageRaw);
        $messageRaw = str_replace("<@&680277216046874636>", "", $messageRaw);
        $channelId = $request->get('channel_id');
        $messageArray = explode(' ', str_replace("\n", " ", $messageRaw));
        $messageArray = array_values(array_filter($messageArray, function ($v) {
            return $v != "";
        }, 0));
        $pokemonName = $messageArray[0];

        preg_match("/Rank.*/", $messageRaw, $rank);
        preg_match("/<:CP:705082200583831582> .*/", $messageRaw, $currentCP);
        preg_match("/<:MS:705082254162002091>:.*/", $messageRaw, $move);
        preg_match("/<:MS:705082254162002091>:.*/", $messageRaw, $move);
        preg_match("/<:DSP:703419665132814396>:.*/", $messageRaw, $dps);
        preg_match("/<:Stardust:703420173289390150>.*.K/", $messageRaw, $stardust);
        preg_match("/<:Candy:705082286714126416>.*/", $messageRaw, $candy);
        preg_match("/<:IV:705082225066115142>:.*/", $messageRaw, $iv);
        preg_match("/<:CP:705082200583831582>:.*\d /", $messageRaw, $cp);
        preg_match("/<:LVL:705082168598200331>:.*/", $messageRaw, $lvl);
        preg_match("/sec.*\n.*/", $messageRaw, $countryData);
        $country = str_replace("sec\n", "", $countryData[0]);
        $count = count($messageArray);
        $message = "$messageArray[0] $messageArray[1] $messageArray[2] \n";
        $rankArray = explode(' ', $rank[0]);
        $message .= "Rank $rankArray[1] $rankArray[2] $rankArray[3] $rankArray[5] \n";
        $message .= "Stardust: " . str_replace("<:Stardust:703420173289390150> ", "", $stardust[0]) . "  Candy: " . str_replace("<:Candy:705082286714126416> ", "", $candy[0]) . " \n";
        $message .= "🅟🅞🅚🅔🅗🅤🅑 🅟🅥🅟 \n";
        $message .= "CP: " . str_replace("<:CP:705082200583831582>: ", "", $cp[0]) . " LVL: " . str_replace("<:LVL:705082168598200331>: ", "", $lvl[0]) . "  \n";
        $message .= "IV: " . str_replace("<:IV:705082225066115142>: ", "", $iv[0]) . "  \n";
        $message .= "Moves: " . str_replace("<:MS:705082254162002091>: ", "", $move[0]) . "\n";
        $message .= "DSP: " . str_replace("<:DSP:703419665132814396>: ", "", $dps[0]) . "\n";
        $message .= $country . " \n";;
        $message .= $messageArray[$count - 2] . $messageArray[$count - 1] . " \n";

        $pokemonRegistrations = $this->getListRegistration($channelId, $pokemonName, $iv[0], $cp[0], $lvl[0], $country);
        foreach ($pokemonRegistrations as $registration) {
            $registration->notify(new PokemonRegistrationNotification($registration, $message));
        }
        return json_encode(['success' => true]);
    }

    function getListRegistration($channelId, $pokemonName, $iv, $cp, $level, $country)
    {
        return PokemonRegistration::where([
            ['channel_id', 'like', "%" . $channelId . "%"],
            ['pokemon_name', 'like', "%" . $pokemonName . "%"],
            ['iv', '>=', $iv],
            ['cp', '>=', $cp],
            ['lvl', '>=', $level],
            ['country', 'like', "%" . $country . "%"],
            ['status', 1]
        ])->groupby('discord_user_id')->get();
    }
}
