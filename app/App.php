<?php

namespace App;

use App\Enums\RequestMethod;
use App\Exceptions\RouteNotFoundException;
use App\Router;

class App
{
    public function __construct(
        private readonly Router $router,
        private readonly Config $config
    ) {
    }

    public function run()
    {
        try {
            // resolve the route and return response
            echo $this->router->resolve(
                $_SERVER['REQUEST_URI'],
                RequestMethod::from(strtolower($_SERVER['REQUEST_METHOD']))
            );
        } catch (RouteNotFoundException $rnf) {
            http_response_code(404);

            echo $rnf->getMessage();
        } catch (\Throwable $t) {
            http_response_code(500);

            echo $t->getMessage();
        }
    }
}
