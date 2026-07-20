<?php

namespace AlexRoden\LibraryApiPhp\Foundation\Providers;

use AlexRoden\LibraryApiPhp\Bus\EventBus;
use AlexRoden\LibraryApiPhp\Config\Config;
use AlexRoden\LibraryApiPhp\Foundation\Container;

class EventServiceProvider
{
    public static function register(Container $container): void {
        $bus = $container->get(EventBus::class);
        foreach (Config::get('events') as $event => $listener) {
            $bus->listen(
                $event,
                $container->make($listener)
            );
        }
    }
}