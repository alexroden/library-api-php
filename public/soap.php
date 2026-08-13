<?php

use AlexRoden\LibraryApiPhp\Config\Config;
use AlexRoden\LibraryApiPhp\Soap\BookService;

require __DIR__ . '/../vendor/autoload.php';

$config = Config::get('soap');

if (
    !isset($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW'])
    || $_SERVER['PHP_AUTH_USER'] !== $config['username']
    || $_SERVER['PHP_AUTH_PW'] !== $config['password']
) {
    header('WWW-Authenticate: Basic realm="Book Service"');
    header('HTTP/1.1 401 Unauthorized');
    header('Content-Type: text/plain');

    echo 'Authentication required';

    exit;
}

$server = new SoapServer(__DIR__ . '/soap/books.wsdl');
$server->setClass(
    BookService::class
);

$server->handle();