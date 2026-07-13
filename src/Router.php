<?php

namespace App;

class Router
{
    private array $routes = [];
    private string $prefix = '';

    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        $handler = $this->routes[$method][$uri] ?? null;

        if ($handler === null) {
            http_response_code(404);
            echo "404 Not Found";
            return;
        }

        if (is_callable($handler)) {
            $handler();
            return;
        }

        [$controller, $action] = $handler;

        (new $controller())->{$action}();
    }

    public function get(string $path, callable|array $handler): void
    {
        $this->addRoute('GET', $path, $handler);
    }

    public function post(string $path, callable|array $handler): void
    {
        $this->addRoute('POST', $path, $handler);
    }

    public function prefix(string $prefix, callable $callback): void
    {
        $previousPrefix = $this->prefix;

        $this->prefix .= $prefix;

        $callback($this);

        $this->prefix = $previousPrefix;
    }

    private function addRoute(string $method, string $path, callable|array $handler): void
    {
        $path = $this->prefix . $path;

        // Remove duplicate slashes
        $path = preg_replace('#/+#', '/', $path);

        $this->routes[$method][$path] = $handler;
    }
}