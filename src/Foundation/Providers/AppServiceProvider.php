<?php

namespace AlexRoden\LibraryApiPhp\Foundation\Providers;

use AlexRoden\LibraryApiPhp\Foundation\Container;
use AlexRoden\LibraryApiPhp\Mail\Mail;
use AlexRoden\LibraryApiPhp\Mail\Mailer;

class AppServiceProvider
{
    public static function register(Container $container): void
    {
        $container->singleton(
            Mailer::class,
            new Mail()
        );
    }
}