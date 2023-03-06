<?php

namespace App;

use App\Enums\RequestMethod;
use App\Exceptions\RouteNotFoundException;

class Router
{
    protected array $routes = [];

    public function __construct(protected readonly Container $container)
    {
    }

    public function register(
        string $route,
        callable|array $callback,
        RequestMethod $method = RequestMethod::GET
    ) {
        $this->routes[$method->value][$route] = $callback;
    }

    public function __call($method, $args)
    {
        // try to create the enum from passed method
        // if its an invalid request method then throw an error
        $requestMethod = RequestMethod::tryFrom($method);
        if (is_null($requestMethod)) {
            throw new \BadMethodCallException('Undefined method ' . $method);
        }

        // add the route to routes
        $this->register(...[...$args, $requestMethod]);
    }

    public function resolve(string $requestUri, RequestMethod $requestMethod)
    {
        // get route without the query string
        $route = explode('?', $requestUri)[0];

        // if its not registered throw an error
        if (!isset($this->routes[$requestMethod->value][$route])) {
            throw new RouteNotFoundException('Route ' . $route . ' not found');
        }

        // if its a callback then call and return the value
        $target = $this->routes[$requestMethod->value][$route];
        if (is_callable($target)) {
            return call_user_func($target);
        }

        // if its a class and method then try to instantiate
        // the object and call the method if exists
        if (is_array($target) && count($target) == 2) {
            [$class, $method] = $target;

            if (class_exists($class)) {
                // resolve the required controller class using container
                // this will also help injecting the dependencies directly
                $class = $this->container->get($class);

                if (method_exists($class, $method)) {
                    return call_user_func([$class, $method]);
                }
            }
        }

        // route is not present
        throw new RouteNotFoundException('Route ' . $route . ' not found');
    }
}
