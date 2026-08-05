<?php

/** @var AlexRoden\LibraryApiPhp\Router $router */

use AlexRoden\LibraryApiPhp\Enums\Permissions;
use AlexRoden\LibraryApiPhp\Http\Controllers\AuthorController;
use AlexRoden\LibraryApiPhp\Http\Controllers\BookController;
use AlexRoden\LibraryApiPhp\Http\Controllers\CategoryController;
use AlexRoden\LibraryApiPhp\Http\Controllers\CouncilController;
use AlexRoden\LibraryApiPhp\Http\Controllers\HealthController;
use AlexRoden\LibraryApiPhp\Http\Controllers\LibraryController;
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
            $router->get('/', [CouncilController::class, 'list'], ['permission:'.Permissions::COUNCILS_LIST]);
            $router->get('/{council}', [CouncilController::class, 'get'], ['permission:'.Permissions::COUNCILS_GET]);
            $router->put('/{council}', [CouncilController::class, 'update'], ['permission:'.Permissions::COUNCILS_UPDATE]);
            $router->delete('/{council}', [CouncilController::class, 'delete'], ['permission:'.Permissions::COUNCILS_DELETE]);
        });
        $router->group(['prefix' => '/libraries'], function ($router) {
            $router->post('/', [LibraryController::class, 'create', ['permission:'.Permissions::LIBRARIES_CREATE]]);
            $router->get('/', [LibraryController::class, 'list', ['permission:'.Permissions::LIBRARIES_LIST]]);
            $router->get('/{library}', [LibraryController::class, 'get', ['permission:'.Permissions::LIBRARIES_GET]]);
            $router->put('/{library}', [LibraryController::class, 'update', ['permission:'.Permissions::LIBRARIES_UPDATE]]);
            $router->delete('/{library}', [LibraryController::class, 'delete', ['permission:'.Permissions::LIBRARIES_DELETE]]);
        });
        $router->group(['prefix' => '/authors'], function ($router) {
            $router->post('/', [AuthorController::class, 'create', ['permission:'.Permissions::AUTHORS_CREATE]]);
            $router->get('/', [AuthorController::class, 'list', ['permission:'.Permissions::AUTHORS_LIST]]);
            $router->get('/{author}', [AuthorController::class, 'get', ['permission:'.Permissions::AUTHORS_GET]]);
            $router->put('/{author}', [AuthorController::class, 'update', ['permission:'.Permissions::AUTHORS_UPDATE]]);
            $router->delete('/{author}', [AuthorController::class, 'delete', ['permission:'.Permissions::AUTHORS_DELETE]]);
        });
        $router->group(['prefix' => '/categories'], function ($router) {
            $router->post('/', [CategoryController::class, 'create', ['permission:'.Permissions::CATEGORIES_CREATE]]);
            $router->get('/', [CategoryController::class, 'list', ['permission:'.Permissions::CATEGORIES_LIST]]);
            $router->get('/{category}', [CategoryController::class, 'get', ['permission:'.Permissions::CATEGORIES_GET]]);
            $router->put('/{category}', [CategoryController::class, 'update', ['permission:'.Permissions::CATEGORIES_UPDATE]]);
            $router->delete('/{category}', [CategoryController::class, 'delete', ['permission:'.Permissions::CATEGORIES_DELETE]]);
        });
        $router->group(['prefix' => '/books'], function ($router) {
            $router->post('/', [BookController::class, 'create', ['permission:'.Permissions::BOOKS_CREATE]]);
            $router->get('/', [BookController::class, 'list', ['permission:'.Permissions::BOOKS_LIST]]);
            $router->get('/{book}', [BookController::class, 'get', ['permission:'.Permissions::BOOKS_GET]]);
            $router->put('/{book}', [BookController::class, 'update', ['permission:'.Permissions::BOOKS_UPDATE]]);
            $router->delete('/{book}', [BookController::class, 'delete', ['permission:'.Permissions::BOOKS_DELETE]]);
        });
    });
});

