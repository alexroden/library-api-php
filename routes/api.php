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
        $router->get('/users', [UserController::class, 'list'], ['permission:'.Permissions::USERS_LIST]);
        $router->get('/users/{user}', [UserController::class, 'get'], ['permission:'.Permissions::USERS_GET]);
        $router->put('/users/{user}', [UserController::class, 'update'], ['permission:'.Permissions::USERS_UPDATE]);
        $router->delete('/users/{user}', [UserController::class, 'delete'], ['permission:'.Permissions::USERS_DELETE]);
    });
});
