<?php

/** @var App\Router $router */

use App\Controllers\HealthController;
use App\Middleware\AuthMiddleware;

$router->prefix('/api', function ($router) {
    $router->get('/_health', [HealthController::class, 'index'], [AuthMiddleware::class]);
});
