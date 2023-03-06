<?php

use App\Config;

// add any new config here to register it on boot
$configsToRegister = [
    'constants',
];

$config = new Config();

foreach ($configsToRegister as $configToRegister) {
    $config->register('constants');
}

// return the config for use in app
return $config;
