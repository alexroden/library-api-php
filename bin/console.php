#!/usr/bin/env php
<?php

require_once __DIR__ . '/../vendor/autoload.php';

use AlexRoden\LibraryApiPhp\Commands\SeedCommand;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$command = $argv[1] ?? null;

switch ($command) {
    case 'seed':
        (new SeedCommand())->run();
        break;

    default:
        echo "Unknown command.\n";
        echo "Available commands:\n";
        echo "  seed\n";
        exit(1);
}