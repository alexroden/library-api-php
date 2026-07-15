<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Database\Connection;
use App\Http\Request;
use App\Router;

Connection::getConnection();

$router = new Router();
require __DIR__ . '/../routes/api.php';

$request = Request::capture();
$router->dispatch($request);