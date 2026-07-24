<?php

namespace AlexRoden\LibraryApiPhp;

use AlexRoden\LibraryApiPhp\Foundation\Container;
use AlexRoden\LibraryApiPhp\Http\Exceptions\NotFoundException;
use AlexRoden\LibraryApiPhp\Http\Foundation\Request;
use AlexRoden\LibraryApiPhp\Http\Helpers\JsonResponse;
use AlexRoden\LibraryApiPhp\Http\Middlewares\AuthMiddleware;
use AlexRoden\LibraryApiPhp\Http\Middlewares\PermissionMiddleware;
use AlexRoden\LibraryApiPhp\Models\AbstractModel;
use ReflectionException;
use ReflectionMethod;
use ReflectionNamedType;

class Router
{
    private array $middlewareAliases = [
        'auth' => AuthMiddleware::class,
        'permission' => PermissionMiddleware::class,
    ];

    private array $routes = [];
    private string $prefix = '';
    private array $middlewareStack = [];

    public function __construct(
        private readonly Container $container,
    ) {
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     * @throws NotFoundException
     * @throws ReflectionException
     */
    public function dispatch(Request $request): JsonResponse
    {
        $method = $request->method();
        $uri = $request->uri();

        $route = null;
        $routeParameters = [];
        foreach ($this->routes[$method] ?? [] as $registeredRoute) {
            if (preg_match($registeredRoute['regex'], $uri, $matches)) {
                $route = $registeredRoute;

                array_shift($matches);

                $routeParameters = array_combine(
                    $registeredRoute['parameters'],
                    $matches
                );

                break;
            }
        }

        if ($route === null) {
            http_response_code(404);
            throw new NotFoundException(
                "{$route} not found"
            );
        }

        $pipeline = array_reduce(
            array_reverse($route['middleware']),
            function (callable $next, string $middleware) {
                return function (Request $request) use ($middleware, $next) {

                    [$name, $parameterString] = array_pad(
                        explode(':', $middleware, 2),
                        2,
                        null
                    );

                    $parameters = $parameterString
                        ? explode(',', $parameterString)
                        : [];

                    $middlewareClass = $this->middlewareAliases[$name] ?? $name;

                    return (new $middlewareClass())->handle(
                        $request,
                        $next,
                        ...$parameters
                    );
                };
            },
            function (Request $request) use ($route, $routeParameters) {
                $handler = $route['handler'];
                if (is_callable($handler)) {
                    return $handler($request);
                }

                [$controller, $action] = $handler;
                $controller = $this->container->make($controller);

                $reflection = new ReflectionMethod(
                    $controller,
                    $action
                );


                $arguments = [];
                foreach ($reflection->getParameters() as $parameter) {
                    $class = null;

                    $parameterName = $parameter->getName();
                    $type = $parameter->getType();
                    if ($type instanceof ReflectionNamedType) {
                        if ($type->isBuiltin()) {
                            continue;
                        }

                        $class = $type->getName();

                        /*
                         * Route model binding
                         */
                        if (
                            array_key_exists($parameterName, $routeParameters)
                            && is_subclass_of($class, AbstractModel::class)
                        ) {
                            $model = $class::find(
                                (int) $routeParameters[$parameterName]
                            );

                            if ($model === null) {
                                throw new NotFoundException(
                                    "{$class} not found"
                                );
                            }

                            $arguments[] = $model;
                            continue;
                        }

                        /*
                         * Request injection
                         */
                        if (is_a($class, Request::class, true)) {
                            if ($request instanceof $class) {
                                $arguments[] = $request;
                            } else {
                                $arguments[] = $class::fromRequest($request);
                            }

                            continue;
                        }
                    }

                    /*
                     * Raw route parameters
                     */
                    if (array_key_exists($parameterName, $routeParameters)) {
                        $arguments[] = $routeParameters[$parameterName];
                        continue;
                    }

                    if ($class !== null) {
                        $arguments[] = $this->container->make($class);
                    }
                }

                return $reflection->invokeArgs(
                    $controller,
                    $arguments
                );
            }
        );

        return $pipeline($request);
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
     * @param array $middleware
     * @param callable $callback
     *
     * @return void
     */
    public function middleware(
        array $middleware,
        callable $callback,
    ): void {
        $previous = $this->middlewareStack;

        $this->middlewareStack = array_merge(
            $this->middlewareStack,
            $middleware
        );

        $callback($this);

        $this->middlewareStack = $previous;
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
     * @param string $path
     * @param callable|array $handler
     * @param array $middleware
     *
     * @return void
     */
    public function put(
        string $path,
        callable|array $handler,
        array $middleware = [],
    ): void {
        $this->addRoute('PUT', $path, $handler, $middleware);
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

        $parameters = [];

        $regex = preg_replace_callback(
            '/\{([^}]+)\}/',
            function ($matches) use (&$parameters) {
                $parameters[] = $matches[1];
                return '([^/]+)';
            },
            $path
        );

        $this->routes[$method][] = [
            'path' => $path,
            'regex' => '#^'.$regex.'$#',
            'parameters' => $parameters,
            'handler' => $handler,
            'middleware' => array_merge(
                $this->middlewareStack,
                $middleware
            ),
        ];
    }
}