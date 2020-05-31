<?php

const HOST = 'http://127.0.0.1:8000';

const IV100_ID = '701624383579357265';
const IV100_NAME = '100iv';

const IV100_LVL30_ID = '701625607133462528';
const IV100_LVL30_NAME = '100iv-lvl30';

const PVP_RANK1_ID = '705080025413845035';
const PVP_RANK1_NAME = 'pvp-rank1-1';

const CHANNEL_REGISTER_ID = '715443208112308244';

const REGISTER_URL = HOST . "/api/pokemon-registrations";
const NOTIFY_OFF_URL = HOST . "/api/pokemon-registrations-off";

const DISCORD_BOT_TOKEN = "";


const HELP_MESSAGE = "\n**List of Commands**\n" .
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
    "!notify off: off all notification\n";
?>
