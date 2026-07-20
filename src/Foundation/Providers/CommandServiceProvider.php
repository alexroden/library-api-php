<?php

namespace AlexRoden\LibraryApiPhp\Foundation\Providers;

use AlexRoden\LibraryApiPhp\Bus\CommandBus;
use AlexRoden\LibraryApiPhp\Config\Config;
use AlexRoden\LibraryApiPhp\Foundation\Container;

class CommandServiceProvider
{
    public static function register(Container $container): void
    {
        $bus = $container->get(CommandBus::class);
        foreach (Config::get('commands') as $command => $handler) {
            $bus->register(
                $command,
                $container->make($handler),
            );
        }
    }
}