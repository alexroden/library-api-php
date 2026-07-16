<?php

/** @var AlexRoden\LibraryApiPhp\Router $router */

use AlexRoden\LibraryApiPhp\Http\Controllers\HealthController;
use AlexRoden\LibraryApiPhp\Http\Controllers\UserController;

$router->prefix('/api', function ($router) {
    $router->get('/_health', [HealthController::class, 'index']);
    $router->post('/auth', [UserController::class, 'auth']);
    $router->middleware(['auth'], function ($router) {
        $router->get('/user', [UserController::class, 'getAuthenticatedUser']);
        $router->post('/users', [UserController::class, 'create'], ['permission:user.create']);
    });
});
