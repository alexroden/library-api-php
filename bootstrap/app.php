<?php

use AlexRoden\LibraryApiPhp\Bus\CommandBus;
use AlexRoden\LibraryApiPhp\Bus\EventBus;
use AlexRoden\LibraryApiPhp\Database\Connection;
use AlexRoden\LibraryApiPhp\Foundation\Container;
use AlexRoden\LibraryApiPhp\Foundation\Providers\AppServiceProvider;
use AlexRoden\LibraryApiPhp\Foundation\Providers\CommandServiceProvider;
use AlexRoden\LibraryApiPhp\Foundation\Providers\EventServiceProvider;
use Dotenv\Dotenv;

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->safeLoad();

function createApplication(bool $testing = false) : Container
{
    Connection::getConnection();

    $container = new Container();

    $commandBus = new CommandBus();
    $container->singleton(CommandBus::class, $commandBus);

    $eventBus = new EventBus();
    $container->singleton(EventBus::class, $eventBus);

    AppServiceProvider::register($container);
    if (!$testing) EventServiceProvider::register($container);
    CommandServiceProvider::register($container);

    return $container;
}

