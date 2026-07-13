<?php

/** @var App\Router $router */

use App\Controllers\HealthController;

$router->prefix('/api', function ($router) {
    $router->get('/_health', [HealthController::class, 'index']);
});
