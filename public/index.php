<?php

use AlexRoden\LibraryApiPhp\Http\Exceptions\AbstractHttpException;
use AlexRoden\LibraryApiPhp\Http\Exceptions\ValidationException;
use AlexRoden\LibraryApiPhp\Http\Foundation\Request;

$router = require_once __DIR__ . '/../bootstrap/router.php';

$request = Request::capture();

try {
    $response = $router->dispatch($request);
    $response->send();
} catch (AbstractHttpException|ValidationException $e) {
    http_response_code($e->statusCode());

    header('Content-Type: application/json');

    $response = [
        'message' => $e->getMessage(),
    ];

    if ($e instanceof ValidationException && count($e->errors()) > 0) {
        $response['errors'] = $e->errors();
    }

    echo json_encode($response);
} catch (Throwable $e) {
    http_response_code(500);

    header('Content-Type: application/json');

    echo json_encode([
        'message' => env('APP_DEBUG', false)
            ? $e->getMessage()
            : 'Internal Server Error',
    ]);
}
