<?php

namespace App\Http\Controllers;

use App\Model\Country;
use App\Model\Pokemon;
use App\Model\PokemonRegistration;
use App\Notifications\PokemonRegistrationNotification;
use Illuminate\Http\Request;

const IV100_ID = '701624383579357265,701625607133462528';

const PVP_RANK1_ID = '705080025413845035';
const PVP_RANK5_ID = '705080266498244637';
const PVP_RANK10_ID = '717389468327477348';
const PVP_RANK20_ID = '717389570513174571';

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
        $channelId = $request->get('channel_id');
        $messageArray = explode(" **", $messageRaw);
        preg_match("/\s\d{0,4}/", $messageArray[3], $cp);
        $dataCountry = explode("> ", $messageArray[4]);

        $pokemonRegistrations = PokemonRegistration::where(['channel_id' => IV100_ID, 'status' => 1])
            ->orWhere([
                ['name', 'like', "%" . $pokemonName . "%"],
                ['channel_id', IV100_ID],
                ['status', 1],
            ])->groupby('discord_user_id')->get();
        preg_match("/DSP.{13}/", $messageArray[1], $dsp);
        $message = "**A $pokemonName spawned!!**\n";
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
        preg_replace("/\[.*]/", "", $messageRaw);
        preg_replace("/<@&680277216046874636>/", "", $messageRaw);
        $messageRaw = str_replace("<@&680277216046874636>","",$messageRaw);
        $channelId = $request->get('channel_id');
        $messageArray = explode(' ', str_replace("\n", " ", $messageRaw));
        $messageArray = array_values(array_filter($messageArray, function ($v) {
            return $v != "";
        }, 0));
        $pokemonRegistrations = PokemonRegistration::where([['channel_id', 'like', "%" . $channelId . "%"], ['status', 1]])
            ->orWhere([['name', 'like', "%" . $messageArray[0] . "%"], ['channel_id', 'like', "%" . $channelId . "%"], ['status', 1]])
            ->groupby('discord_user_id')->get();

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
        preg_match("/sec.*\n.*/", $messageRaw, $country);
        $count = count($messageArray);
        $message = "$messageArray[0] $messageArray[1] $messageArray[2] \n";
        $rankArray = explode(' ', $rank[0]);
        $message .= "Rank $rankArray[1] $rankArray[2] $rankArray[3] $rankArray[5] \n";
        $message .= "Stardust: ".str_replace("<:Stardust:703420173289390150> ", "", $stardust[0])."  Candy: ".str_replace("<:Candy:705082286714126416> ", "", $candy[0])." \n";
        $message .= "🅟🅞🅚🅔🅗🅤🅑 🅟🅥🅟 \n";
        $message .= "CP: ".str_replace("<:CP:705082200583831582>: ", "", $cp[0])." LVL: ".str_replace("<:LVL:705082168598200331>: ", "", $lvl[0])."  \n";
        $message .= "IV: ".str_replace("<:IV:705082225066115142>: ", "", $iv[0])."  \n";
        $message .= "Moves: " . str_replace("<:MS:705082254162002091>: ", "", $move[0]) . "\n";
        $message .= "DSP: " . str_replace("<:DSP:703419665132814396>: ", "", $dps[0]) . "\n";
        $message .= str_replace("sec\n", "", $country[0]) . " \n";;
        $message .= $messageArray[$count - 2] . $messageArray[$count - 1] . " \n";

        foreach ($pokemonRegistrations as $registration) {
            $registration->notify(new PokemonRegistrationNotification($registration, $message));
        }
        return json_encode(['success' => true]);
    }

}
