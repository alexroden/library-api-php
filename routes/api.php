<?php

/** @var AlexRoden\LibraryApiPhp\Router $router */

use AlexRoden\LibraryApiPhp\Controllers\HealthController;
use AlexRoden\LibraryApiPhp\Middleware\AuthMiddleware;

$router->prefix('/api', function ($router) {
    $router->get('/_health', [HealthController::class, 'index'], [AuthMiddleware::class]);
});
