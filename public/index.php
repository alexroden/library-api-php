<?php

require __DIR__ . '/../vendor/autoload.php';

use AlexRoden\LibraryApiPhp\Database\Connection;
use AlexRoden\LibraryApiPhp\Http\Request;
use AlexRoden\LibraryApiPhp\Router;

Connection::getConnection();

$router = new Router();
require __DIR__ . '/../routes/api.php';

$request = Request::capture();
$router->dispatch($request);