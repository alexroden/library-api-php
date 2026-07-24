<?php

/** @var AlexRoden\LibraryApiPhp\Router $router */

use AlexRoden\LibraryApiPhp\Enums\Permissions;
use AlexRoden\LibraryApiPhp\Http\Controllers\CouncilController;
use AlexRoden\LibraryApiPhp\Http\Controllers\HealthController;
use AlexRoden\LibraryApiPhp\Http\Controllers\UserController;

$router->prefix('/api', function ($router) {
    $router->get('/_health', [HealthController::class, 'index']);
    $router->post('/auth', [UserController::class, 'auth']);
    $router->post('/register', [UserController::class, 'register']);
    $router->middleware(['auth'], function ($router) {
        $router->get('/me', [UserController::class, 'me']);
        $router->group(['prefix' => '/users'], function ($router) {
            $router->post('/', [UserController::class, 'create'], ['permission:'.Permissions::USERS_CREATE]);
            $router->get('/', [UserController::class, 'list'], ['permission:'.Permissions::USERS_LIST]);
            $router->get('/{user}', [UserController::class, 'get'], ['permission:'.Permissions::USERS_GET]);
            $router->put('/{user}', [UserController::class, 'update'], ['permission:'.Permissions::USERS_UPDATE]);
            $router->delete('{user}', [UserController::class, 'delete'], ['permission:'.Permissions::USERS_DELETE]);
        });
        $router->group(['prefix' => '/councils'], function ($router) {
            $router->post('/', [CouncilController::class, 'create'], ['permission:'.Permissions::COUNCILS_CREATE]);
        });
    });
});

