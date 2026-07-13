<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Http\Request;
use App\Router;

$router = new Router();
require __DIR__ . '/../routes/api.php';

$request = Request::capture();

$router->dispatch($request);