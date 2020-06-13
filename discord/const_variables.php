<?php

const HOST = 'http://127.0.0.1:8000';

const CHANNEL_LIST = [
    'test-notify' => '715443208112308244',
    'beta-tester' => '721197401439993876',
    'notify-bot' => '721200114235867196',
    'iv100' => '701624383579357265',
    'iv100_lvl_30' => '701625607133462528',
    'iv90' => '701640369871388713',
    'ultra-rare' => '701640141520896000',
    'ditto-master' => '701626291056541737',
    'gen1' => '701640503459840031',
    'gen2' => '701640558140981251',
    'gen3' => '701640619600117761',
    'gen4' => '701640717059096576',
    'gen5' => '701640774789365820',
    'rank1' => '705080025413845035',
    'rank2-5' => '705080266498244637',
    'rank6-10' => '717389468327477348',
    'rank11-20' => '717389570513174571',
    'cp-2500' => '701627850322870382',
];
const URL_LIST = [
    'register' => HOST . "/api/pokemon-registrations",
    'notify' => HOST . "/api/pokemon-registrations-off",
    'list' => HOST . "/api/pokemon-registrations-list",
    'appear' => HOST . "/api/pokemon-appear",
    'pvp-appear' => HOST . "/api/pokemon-pvp-appear"
];

const MESSAGE_LIST = [
    'help_new' => "\n**LIST OF COMMANDS**\n" .
        "Bot commands: prefix !notify\n\n" .
        "!notify help: Show all the commands\n\n" .
        "!notify channel_name filter1:value1 filter2:value2 .. filterN:valueN\n\n" .
        "**CURRENT CHANNEL LIST**\n" .
        "iv100 | iv90 | ultra-rare | ditto-master | gen1 | gen2 | gen3 | gen4 | gen5 | cp-2500\n".
        "rank1 | rank2-5 | rank6-10 | rank11-20\n\n" .
        "**FILTER LIST**\n" .
        "pokemon_name: | iv: | cp: | level: | country: \n\n" .
        "**EXAMPLES**\n" .
        "● !notify iv100\n" .
        "● !notify iv100 pokemon_name:Stunfisk\n" .
        "● !notify iv100 pokemon_name:Stunfisk country:Vietnam level:20\n" .
        "● !notify iv100 pokemon_name:Stunfisk country:Vietnam level:20 cp:200\n\n" .

        "● IV is invalid in iv100 channel\n" .
        "● Only 1 filter / channel\n" .
        "\n" .
        "**!notify list**: To list all notifications\n" .
        "\n" .
        "**!notify off**: Turn off all notification\n",
];


?>

