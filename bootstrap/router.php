<?php

use App\Container;
use App\Router;

return function (Container $container) {
    // create router
    $router = new Router($container);

    // setup routes
    $configureRoutes = require_once dirname(__DIR__) . '/routes/routes.php';

    $configureRoutes($router);

    // return the router for use in app
    return $router;
};
