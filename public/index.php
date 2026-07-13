<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Router;

$router = new Router();
require __DIR__ . '/../routes/api.php';

$router->dispatch();