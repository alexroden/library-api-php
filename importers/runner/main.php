<?php

use AlexRoden\Importers\Queue\SqsPublisher;
use AlexRoden\Importers\Runner\BookImportRunner;
use AlexRoden\Importers\Soap\BookClient;
use AlexRoden\LibraryApiPhp\Config\Config;

require __DIR__ . '/../bootstrap.php';

$soap = Config::get('soap');
$queue = Config::get('queue');

try {
    $runner = new BookImportRunner(
        new BookClient(
            $soap['baseUrl'],
            $soap['username'],
            $soap['password'],
        ),
        new SqsPublisher(
            $queue['books']['url'],
            $queue,
        ),
        $queue['books']['batchSize'],
    );

    $runner->run();
} catch (Throwable $e) {
    fwrite(STDERR, 'Import failed: ' . $e->getMessage() . PHP_EOL);

    exit(1);
}
