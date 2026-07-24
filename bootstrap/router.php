<?php

use AlexRoden\LibraryApiPhp\Router;

require_once __DIR__ . '/app.php';

$container = createApplication();
$router = new Router($container);

require __DIR__ . '/../routes/api.php';
require __DIR__ . '/../routes/docs.php';

return $router;