<?php

use Symfony\Component\VarDumper\VarDumper;

if (! function_exists('dd')) {
    function dd(mixed ...$vars): never
    {
        foreach ($vars as $var) {
            VarDumper::dump($var);
        }

        exit;
    }
}

if (! function_exists('dump')) {
    function dump(mixed ...$vars): void
    {
        foreach ($vars as $var) {
            VarDumper::dump($var);
        }
    }
}

if (! function_exists('env')) {
    function env(string $key, mixed $default = null): mixed
    {
        return $_ENV[$key] ?? $_SERVER[$key] ?? $default;
    }
}

