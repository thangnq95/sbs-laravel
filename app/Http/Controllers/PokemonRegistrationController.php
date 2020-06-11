<?php

namespace App\Http\Controllers;

use App\Model\Country;
use App\Model\Pokemon;
use App\Model\PokemonRegistration;
use App\Notifications\PokemonRegistrationNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            $pokemonRegistration->pokemon_name = isset($pokemon->name) ? $pokemon->name : "";
            $pokemonRegistration->no = isset($pokemon->no) ? $pokemon->no : null;
            $pokemonRegistration->country = isset($country->name) ? $country->name : "";
            $pokemonRegistration->iv = ($request->has('iv')) ? $request->get('iv') : 0;
            $pokemonRegistration->cp = ($request->has('cp')) ? $request->get('cp') : 0;
            $pokemonRegistration->level = ($request->has('level')) ? $request->get('level') : 0;

            $messageReply = "";
            $messageReply .= ($pokemonRegistration->pokemon_name != "") ?  $pokemonRegistration->pokemon_name . " " : "";
            $messageReply .= ($pokemonRegistration->channel_name != "") ? strtoupper($pokemonRegistration->channel_name) . " | " : "";
            $messageReply .= ($pokemonRegistration->country != "") ? "Country: " . strtoupper($pokemonRegistration->country) . " | " : "";
            $messageReply .= ($pokemonRegistration->iv != 0) ? strtoupper("IV " . $pokemonRegistration->iv) . " | " : "";
            $messageReply .= ($pokemonRegistration->cp != 0) ? strtoupper("CP " . $pokemonRegistration->cp) . " | " : "";
            $messageReply .= ($pokemonRegistration->level != 0) ? strtoupper("LVL " . $pokemonRegistration->level) . " | " : "";
            $messageReply .= "Registered\n";
            $pokemonRegistration->save();
//            $pokemonRegistration->notify(new PokemonRegistrationNotification($pokemonRegistration, $messageReply));
            return json_encode([
                'success' => true,
                'message' => $messageReply,
                'data' => $pokemonRegistration,
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
     * Turn off notify for user.
     *
     * @param Request $request
     * @return false|object
     */
    public function notifyList(Request $request)
    {
        $discord_user_id = $request->get('discord_user_id');
        $pokemonRegistrations = PokemonRegistration::where([
            ['discord_user_id', $discord_user_id],
            ['status', 1]
        ])->get();
        $message = "";
        foreach ($pokemonRegistrations as $pokemonRegistration) {
            $message .= "● ";
            $message .= ($pokemonRegistration->channel_name != "") ? strtoupper($pokemonRegistration->channel_name) . " | " : "";
            $message .= ($pokemonRegistration->pokemon_name != "") ? "Pokemon name: " . strtoupper($pokemonRegistration->pokemon_name) . " | " : "";
            $message .= ($pokemonRegistration->country != "") ? "Country: " . strtoupper($pokemonRegistration->country) . " | " : "";
            $message .= ($pokemonRegistration->iv != 0) ? strtoupper("IV " . $pokemonRegistration->iv) . " | " : "";
            $message .= ($pokemonRegistration->cp != 0) ? strtoupper("CP " . $pokemonRegistration->cp) . " | " : "";
            $message .= ($pokemonRegistration->level != 0) ? strtoupper("LVL " . $pokemonRegistration->level) . " | " : "";
            $message .= "Registered\n";
        }
        return json_encode([
            'success' => true,
            'message' => $message
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
        $message .= "```" . str_replace("✰", "", $dataCountry[3]) . "``` ";
        $message .= "BY: 🅟🅞🅚🅔🅗🅤🅑\n";
        $message .= "--------------------------------------------\n";

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
        preg_match("/<:IV:705082225066115142>:.*/", $messageRaw, $ivData);
        preg_match("/<:CP:705082200583831582>:.*\d /", $messageRaw, $cpData);
        preg_match("/<:LVL:705082168598200331>:.*/", $messageRaw, $lvlData);
        preg_match("/sec.*\n.*/", $messageRaw, $countryData);
        $country = str_replace("sec\n", "", $countryData[0]);
        $count = count($messageArray);
        $message = "$messageArray[0] $messageArray[1] $messageArray[2] \n";
        $rankArray = explode(' ', $rank[0]);

        $cp = str_replace("<:CP:705082200583831582>: ", "", $cpData[0]);
        $lvl = str_replace("<:LVL:705082168598200331>: ", "", $lvlData[0]);
        $iv = str_replace("<:IV:705082225066115142>: ", "", $ivData[0]);

        $message .= "Rank $rankArray[1] $rankArray[2] $rankArray[3] $rankArray[5] \n";
        $message .= "Stardust: " . str_replace("<:Stardust:703420173289390150> ", "", $stardust[0]) . "  Candy: " . str_replace("<:Candy:705082286714126416> ", "", $candy[0]) . " \n";
        $message .= "🅟🅞🅚🅔🅗🅤🅑 🅟🅥🅟 \n";
        $message .= "CP: " . $cp . " LVL: " . $lvl . "  \n";
        $message .= "IV: " . $iv . "  \n";
        $message .= "Moves: " . str_replace("<:MS:705082254162002091>: ", "", $move[0]) . "\n";
        $message .= "DSP: " . str_replace("<:DSP:703419665132814396>: ", "", $dps[0]) . "\n";
        $message .= $country . " \n";;
        $message .= $messageArray[$count - 2] . $messageArray[$count - 1] . " \n";
        $message .= "--------------------------------------------\n";
        $pokemonRegistrations = $this->getListRegistration($channelId, $pokemonName, $iv, $cp, $lvl, $country);

        foreach ($pokemonRegistrations as $registration) {
            $registration->notify(new PokemonRegistrationNotification($registration, $message));
        }
        return json_encode(['success' => true]);
    }

    function getListRegistration($channelId, $pokemonName, $iv, $cp, $level, $country)
    {
        preg_match("/\d*%/", $iv, $ivData);
        if (!empty($ivData)) {
            $iv = str_replace("%", "", $ivData[0]);
        }
        $country = preg_replace('/[^A-Za-z0-9\-]/', '', $country);
        return PokemonRegistration::where([
            ['channel_id', 'like', "%" . $channelId . "%"],
            ['iv', '<=', intval($iv)],
            ['cp', '<=', intval($cp)],
            ['level', '<=', intval($level)],
            ['status', 1]
        ])->where(function ($qPokeName) use ($pokemonName) {
            $qPokeName->where('pokemon_name', 'like', "%" . $pokemonName . "%")
                ->orWhere('pokemon_name', '=', "");
        })->where(function ($qCountry) use ($country) {
            $qCountry->whereRaw('locate(country,?) > 0', [$country])
                ->orWhere('country', '=', "");
        })->groupby('discord_user_id')->get();
    }
}
