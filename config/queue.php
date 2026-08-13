<?php

/*
|--------------------------------------------------------------------------
| Queue
|--------------------------------------------------------------------------
|
| Connection details for the SQS-compatible queue that carries book ids from
| the import runner to the workers. Locally that is the ElasticMQ container
| `make start` brings up; the same keys point at real SQS in a deployment.
|
| Every value is read straight from the environment with no fallback (bar the
| batch size), so a key missing from `.env` is a fatal error rather than a
| silent default.
|
*/

return [

    /*
    |--------------------------------------------------------------------------
    | Connection
    |--------------------------------------------------------------------------
    |
    | Passed as-is to the `SqsClient` built by `SqsPublisher`/`SqsConsumer`.
    | The endpoint is what points the aws sdk at ElasticMQ instead of the real
    | service; the credentials are unused by ElasticMQ but the sdk still
    | insists on being given a pair.
    |
    */
    'endpoint' => $_ENV['SQS_ENDPOINT'],
    'region' => $_ENV['AWS_DEFAULT_REGION'],
    'key' => $_ENV['AWS_ACCESS_KEY_ID'],
    'secret' => $_ENV['AWS_SECRET_ACCESS_KEY'],

    /*
    |--------------------------------------------------------------------------
    | The queue the book importer publishes to, and how many book ids are
    | placed on each message for a worker to pick up.
    |--------------------------------------------------------------------------
    |
    | The batch size is the one value here with a default. It trades off
    | against the visibility timeout in `SqsConsumer`: a batch is only deleted
    | once every id in it succeeded, so a larger batch means fewer messages but
    | more work repeated when one book in it fails.
    |
    */
    'books' => [
        'url' => $_ENV['SQS_QUEUE_URL'],
        'batchSize' => (int) ($_ENV['IMPORT_BATCH_SIZE'] ?? 25),
    ],
];
