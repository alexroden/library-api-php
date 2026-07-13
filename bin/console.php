#!/usr/bin/env php
<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Commands\SeedCommand;
use App\Database\Database;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$command = $argv[1] ?? null;

$database = new Database();

switch ($command) {
    case 'seed':
        (new SeedCommand($database->getConnection()))->run();
        break;

    default:
        echo "Unknown command.\n";
        echo "Available commands:\n";
        echo "  seed\n";
        exit(1);
}