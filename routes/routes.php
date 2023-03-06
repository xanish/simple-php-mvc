<?php

use App\Router;
use App\Controllers\IndexController;
use App\Controllers\UserController;

return function (Router $router) {
    $router->get('/', [IndexController::class, 'index']);
    $router->get('/user', [UserController::class, 'index']);
    $router->post('/user', [UserController::class, 'create']);
};
