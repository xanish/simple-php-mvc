<?php

// commenting out the custom simple autoload in favor of composers autoload
// require_once __DIR__ . '/simple_autoload.php';

// custom project classes get added to composer.json autoload -> psr-4 section
require_once dirname(__DIR__) . '/vendor/autoload.php';

use App\Container;

$container = new Container();

// bootstrap prerequisites
$config = require_once dirname(__DIR__) . '/bootstrap/config.php';
$router = (require_once dirname(__DIR__) . '/bootstrap/router.php')($container);

(new \App\App($router, $config))->run();

// docker run -it --rm -p 8000:8000 -v "$PWD":/usr/src/app -w /usr/src/app php:8.1-cli-alpine php -S 0.0.0.0:8000