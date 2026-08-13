<?php


require __DIR__ . '/../../vendor/autoload.php';


use AlexRoden\Importers\Soap\BookClient;
use AlexRoden\LibraryApiPhp\Config\Config;

$config = Config::get('soap');

try {
    $client = new BookClient($config['baseUrl'], $config['username'], $config['password']);

    $books = $client->getBooks();

    dd($books);

    foreach ($books as $book) {
        echo $book->id . ': ' . $book->title . PHP_EOL;
    }
} catch (SoapFault $e) {
    dd($e);
}

