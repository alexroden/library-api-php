<?php

namespace AlexRoden\LibraryApiPhp;

use AlexRoden\LibraryApiPhp\Http\JsonResponse;
use AlexRoden\LibraryApiPhp\Http\Request;
use ReflectionMethod;
use ReflectionNamedType;

class Router
{
    private array $routes = [];
    private string $prefix = '';

    /**
     * @param Request $request
     *
     * @return void
     * @throws \ReflectionException
     */
    public function dispatch(Request $request): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        $route = $this->routes[$method][$uri] ?? null;
        if ($route === null) {
            http_response_code(404);
            echo "404 Not Found";
            return;
        }

        $pipeline = array_reduce(
            array_reverse($route['middleware']),
            function (callable $next, string $middlewareClass) {
                return function (Request $request) use ($middlewareClass, $next) {
                    return (new $middlewareClass())->handle($request, $next);
                };
            },
            function (Request $request) use ($route) {
                $handler = $route['handler'];

                if (is_callable($handler)) {
                    return $handler($request);
                }

                [$controller, $action] = $handler;

                $controller = new $controller();

                $reflection = new ReflectionMethod($controller, $action);

                $arguments = [];

                foreach ($reflection->getParameters() as $parameter) {
                    $type = $parameter->getType();

                    if (! $type instanceof ReflectionNamedType) {
                        continue;
                    }

                    $class = $type->getName();

                    if ($request instanceof $class) {
                        $arguments[] = $request;
                        continue;
                    }

                    $arguments[] = new $class();
                }

                return $reflection->invokeArgs($controller, $arguments);
            }
        );

        $response = $pipeline($request);
        if ($response instanceof JsonResponse) {
            $response->send();
        }
    }

    /**
     * @param string $path
     * @param callable|array $handler
     * @param array $middleware
     *
     * @return void
     */
    public function get(
        string $path,
        callable|array $handler,
        array $middleware = [],
    ): void {
        $this->addRoute('GET', $path, $handler, $middleware);
    }

    /**
     * @param string $path
     * @param callable|array $handler
     * @param array $middleware
     *
     * @return void
     */
    public function post(
        string $path,
        callable|array $handler,
        array $middleware = [],
    ): void {
        $this->addRoute('POST', $path, $handler, $middleware);
    }

    /**
     * @param string $prefix
     * @param callable $callback
     *
     * @return void
     */
    public function prefix(string $prefix, callable $callback): void
    {
        $previousPrefix = $this->prefix;
        $this->prefix .= $prefix;
        $callback($this);
        $this->prefix = $previousPrefix;
    }

    /**
     * @param string $method
     * @param string $path
     * @param callable|array $handler
     * @param array $middleware
     *
     * @return void
     */
    private function addRoute(
        string $method,
        string $path,
        callable|array $handler,
        array $middleware = [],
    ): void {
        $path = preg_replace('#/+#', '/', $this->prefix . $path);

        $this->routes[$method][$path] = [
            'handler' => $handler,
            'middleware' => $middleware,
        ];
    }
}