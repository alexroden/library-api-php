<?php

/** @var AlexRoden\LibraryApiPhp\Router $router */

use AlexRoden\LibraryApiPhp\Enums\Permissions;
use AlexRoden\LibraryApiPhp\Http\Controllers\HealthController;
use AlexRoden\LibraryApiPhp\Http\Controllers\UserController;

$router->prefix('/api', function ($router) {
    $router->get('/_health', [HealthController::class, 'index']);
    $router->post('/auth', [UserController::class, 'auth']);
    $router->middleware(['auth'], function ($router) {
        $router->get('/me', [UserController::class, 'me']);
        $router->post('/users', [UserController::class, 'create'], ['permission:'.Permissions::USERS_CREATE]);
        $router->get('/users', [UserController::class, 'list'], ['permission:'.Permissions::USERS_GET]);
    });
});
