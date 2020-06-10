<?php

const HOST = 'http://127.0.0.1:8000';

const CHANNEL_LIST = [
    'register' => '715443208112308244',
    'iv100' => '701624383579357265',
    'iv100_lvl_30' => '701625607133462528',
    'iv90' => '701640369871388713',
    'rank1' => '705080025413845035',
    'rank2-5' => '705080266498244637',
    'rank6-10' => '717389468327477348',
    'rank11-20' => '717389570513174571',
    'cp-2500' => '701627850322870382',
];
const URL_LIST = [
    'register' => HOST . "/api/pokemon-registrations",
    'notify' => HOST . "/api/pokemon-registrations-off",
    'appear' => HOST . "/api/pokemon-appear",
    'pvp-appear' => HOST . "/api/pokemon-pvp-appear"
];

const MESSAGE_LIST = [
    'help' => "\n**List of Commands**\n" .
        "Bot commands: prefix !notify\n\n" .
        "!notify help: Show all the commands\n" .
        "\n" .
        "!notify iv (1-100):channel\n" .
        "!notify cp (1-99999):channel\n" .
        "!notify level (1-40):channel\n" .
        "!notify country (keyword):channel\n" .
        "\n" .
        "!notify pokemon_name (keyword):channel\n" .
        "\n" .
        "!notify iv100 (DM all IV100 feed)\n" .
        "!notify iv100 pokemon_name(DM pokemon_name IV100 feed)\n" .
        "!notify rank1 (DM all pvp rank 1)\n" .
        "!notify rank1 pokemon_name(DM pokemon_name pvp rank 1)\n" .
        "\n" .
        "!notify off: off all notification\n",
];


?>

