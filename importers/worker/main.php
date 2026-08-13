<?php

use AlexRoden\Importers\Queue\SqsConsumer;
use AlexRoden\Importers\Soap\BookClient;
use AlexRoden\Importers\Worker\BookImportWorker;
use AlexRoden\LibraryApiPhp\Bus\CommandBus;
use AlexRoden\LibraryApiPhp\Config\Config;

require __DIR__ . '/../bootstrap.php';

/*
 * Unlike the runner, the worker writes to the database, so it boots the
 * application container to get a command bus with every handler registered.
 */
require_once __DIR__ . '/../../bootstrap/app.php';

$soap = Config::get('soap');
$queue = Config::get('queue');

try {
    $container = createApplication();

    $worker = new BookImportWorker(
        new BookClient(
            $soap['baseUrl'],
            $soap['username'],
            $soap['password'],
        ),
        new SqsConsumer(
            $queue['books']['url'],
            $queue,
        ),
        $container->get(CommandBus::class),
    );

    /*
     * The worker never stops on its own, so `docker stop` and ctrl-c are the
     * normal way out of it. Handling the signals rather than letting them kill
     * the process lets the batch in flight finish and be deleted from the
     * queue instead of becoming visible again and being imported twice.
     */
    if (function_exists('pcntl_async_signals')) {
        pcntl_async_signals(true);

        $stop = static function (int $signal) use ($worker): void {
            echo "Received signal {$signal}, shutting down...\n";

            $worker->stop();
        };

        pcntl_signal(SIGTERM, $stop);
        pcntl_signal(SIGINT, $stop);
    }

    $worker->run();
} catch (Throwable $e) {
    fwrite(STDERR, 'Worker failed: ' . $e->getMessage() . PHP_EOL);

    exit(1);
}
