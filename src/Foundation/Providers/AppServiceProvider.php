<?php

namespace AlexRoden\LibraryApiPhp\Foundation\Providers;

use AlexRoden\LibraryApiPhp\Foundation\Container;
use AlexRoden\LibraryApiPhp\Mail\Mail;

class AppServiceProvider
{
    public static function register(Container $container): void
    {
        $container->singleton(
            Mail::class,
            new Mail()
        );
    }
}