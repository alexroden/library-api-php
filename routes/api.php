<?php

/** @var AlexRoden\LibraryApiPhp\Router $router */

use AlexRoden\LibraryApiPhp\Http\Controllers\HealthController;
use AlexRoden\LibraryApiPhp\Http\Controllers\UserController;
use AlexRoden\LibraryApiPhp\Middleware\AuthMiddleware;

$router->prefix('/api', function ($router) {
    $router->get('/_health', [HealthController::class, 'index']);
    $router->post('/auth', [UserController::class, 'auth']);
    $router->get('/user', [UserController::class, 'getAuthenticatedUser'], [AuthMiddleware::class]);
});
