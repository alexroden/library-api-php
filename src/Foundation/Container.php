<?php

namespace AlexRoden\LibraryApiPhp\Foundation;

use ReflectionClass;
use ReflectionException;

class Container
{
    private array $bindings = [];
    private array $instances = [];

    public function get(string $abstract): object
    {
        return $this->instances[$abstract] ?? $this->resolve($abstract);
    }

    public function singleton(string $abstract, object $instance): void
    {
        $this->instances[$abstract] = $instance;
    }

    public function bind(string $abstract, callable $factory): void
    {
        $this->bindings[$abstract] = $factory;
    }

    /**
     * @throws ReflectionException
     */
    public function make(string $class): object
    {
        if (isset($this->instances[$class])) {
            return $this->instances[$class];
        }

        if (isset($this->bindings[$class])) {
            return $this->bindings[$class]($this);
        }

        return $this->resolve($class);
    }

    /**
     * @throws ReflectionException
     */
    private function resolve(string $class): object
    {
        $reflection = new ReflectionClass($class);

        $constructor = $reflection->getConstructor();
        if (!$constructor) {
            return new $class();
        }

        $dependencies = [];

        foreach ($constructor->getParameters() as $parameter) {
            $dependencies[] = $this->make(
                $parameter->getType()->getName()
            );
        }

        return $reflection->newInstanceArgs($dependencies);
    }
}
