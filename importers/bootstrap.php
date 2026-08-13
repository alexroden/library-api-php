<?php

use Dotenv\Dotenv;

require_once __DIR__ . '/../vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Importers run as standalone processes, so they load their own environment.
| In Docker the values arrive as real environment variables via env_file,
| which is why the .env file is optional here.
|--------------------------------------------------------------------------
*/
Dotenv::createImmutable(dirname(__DIR__))->safeLoad();
