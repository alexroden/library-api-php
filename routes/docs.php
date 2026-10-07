<?php

/** @var AlexRoden\LibraryApiPhp\Router $router */

use AlexRoden\LibraryApiPhp\Http\Helpers\JsonResponse;

$router->get('/docs/openapi.json', function () {
    return new JsonResponse(
        json_decode(
            file_get_contents(__DIR__ . '/../public/openapi.json'),
            true
        )
    );
});
