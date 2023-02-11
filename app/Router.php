<?php

namespace App;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Exception\NoConfigurationException;
use Symfony\Component\Routing\RouteCollection;

class Router
{
    protected static array $routes = [];

    public static function add(string $name, string $path): Route
    {
        return self::$routes[$name] = new Route($path);
    }

    public static function init()
    {
        $context = new RequestContext();
        $context->fromRequest(Request::createFromGlobals());
        $routes = new RouteCollection();

        foreach (self::$routes as $name => $route) {
            $routes->add($name, $route);
        }

        // Routing can match routes with incoming requests
        $matcher = new UrlMatcher($routes, $context);
        try {
            $arrayUri = explode('?', $_SERVER['REQUEST_URI']);
            $matcher = $matcher->match($arrayUri[0]);

            // Cast params to int if numeric
            array_walk($matcher, function (&$param) {
                if (is_numeric($param)) {
                    $param = (int)$param;
                }
            });

            call_user_func_array(
                $matcher['_controller'],
                (array)array_slice($matcher, 1, -1)
            );

        } catch (MethodNotAllowedException $e) {
            http_response_code(405);
            echo 'Route method is not allowed.';
        } catch (NoConfigurationException $e) {
            http_response_code(404);
            echo 'Configuration does not exists.';
        } catch (ResourceNotFoundException $e) {
            http_response_code(404);
            echo 'Route does not exists.';
        }
    }


}