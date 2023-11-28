<?php

namespace Account\Infra\Http;

use Account\Infra\Controller\AccountController;
use Exception;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use function FastRoute\simpleDispatcher;

class Kernel
{
    /**
     * @throws Exception
     */
    public function handle(Request $request): Response
    {
        $dispatcher = simpleDispatcher(function (RouteCollector $route) {
            $route->addRoute('POST', '/signup', [AccountController::class, 'create']);
            $route->addRoute('GET', '/account/{id:\d+}', [AccountController::class, 'getAccount']);
            $route->addRoute('POST', '/account/{id:\d+}/balance/increase', [AccountController::class, 'increaseBalance']);
            $route->addRoute('POST', '/account/{id:\d+}/balance/decrease', [AccountController::class, 'decreaseBalance']);
        });

        $httpMethod = $request->getMethod();
        $uri = $request->getRequestUri();
        $pos = strpos($uri, '?');
        if ($pos) {
            $uri = substr($uri, 0, $pos);
        }
        $uri = rawurldecode($uri);
        $routeInfo = $dispatcher->dispatch($httpMethod, $uri);
        $controller = "";
        $method = "";
        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                throw new Exception("Route not found", 404);
            case Dispatcher::METHOD_NOT_ALLOWED:
                throw new Exception("Method not allowed", 405);
            case Dispatcher::FOUND:
                $controller = $routeInfo[1][0];
                $method = $routeInfo[1][1];
                break;
        }
        return call_user_func_array([new $controller($request), $method], $routeInfo[2]);
    }
}