<?php

require __DIR__ . '/../vendor/autoload.php';

use AlexRoden\LibraryApiPhp\Database\Connection;
use AlexRoden\LibraryApiPhp\Http\Exceptions\AbstractHttpException;
use AlexRoden\LibraryApiPhp\Http\Exceptions\ValidationException;
use AlexRoden\LibraryApiPhp\Http\Foundation\Request;
use AlexRoden\LibraryApiPhp\Router;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

Connection::getConnection();

$router = new Router();
require __DIR__ . '/../routes/api.php';
require __DIR__ . '/../routes/docs.php';

$request = new Request();
try {
    $router->dispatch($request);
}  catch (ValidationException $e) {
    http_response_code(422);

    header('Content-Type: application/json');

    echo json_encode([
        'message' => $e->getMessage(),
        'errors' => $e->errors(),
    ]);
} catch (AbstractHttpException $e) {
    http_response_code($e->statusCode());

    header('Content-Type: application/json');

    echo json_encode(['message' => $e->getMessage()]);
} catch (Throwable $e) {
    http_response_code(500);

    header('Content-Type: application/json');

    if (env('APP_DEBUG', false)) {
        echo json_encode([
            'message' => $e->getMessage(),
            'exception' => get_class($e),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTrace(),
        ]);
    } else {
        echo json_encode([
            'message' => 'Internal Server Error',
        ]);
    }

    // Log the exception here
}