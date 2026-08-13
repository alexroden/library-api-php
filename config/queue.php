<?php

return [
    'endpoint' => $_ENV['SQS_ENDPOINT'],
    'region' => $_ENV['AWS_DEFAULT_REGION'],
    'key' => $_ENV['AWS_ACCESS_KEY_ID'],
    'secret' => $_ENV['AWS_SECRET_ACCESS_KEY'],

    /*
    |--------------------------------------------------------------------------
    | The queue the book importer publishes to, and how many book ids are
    | placed on each message for a worker to pick up.
    |--------------------------------------------------------------------------
    */
    'books' => [
        'url' => $_ENV['SQS_QUEUE_URL'],
        'batchSize' => (int) ($_ENV['IMPORT_BATCH_SIZE'] ?? 25),
    ],
];
