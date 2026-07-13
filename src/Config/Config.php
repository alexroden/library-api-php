<?php

namespace App\Config;

use RuntimeException;

final class Config
{
    public static function get(string $key): array
    {
        $parts = explode('.', $key);
        $file = array_shift($parts);

        $path = dirname(__DIR__, 2) . "/config/{$file}.php";
        if (! file_exists($path)) {
            throw new RuntimeException("Config file '{$file}' not found.");
        }

        $config = require $path;
        foreach ($parts as $part) {
            if (! is_array($config) || ! array_key_exists($part, $config)) {
                throw new RuntimeException("Config key '{$key}' not found.");
            }

            $config = $config[$part];
        }

        return $config;
    }
}