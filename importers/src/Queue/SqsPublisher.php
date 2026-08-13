<?php

namespace AlexRoden\Importers\Queue;

use Aws\Sqs\SqsClient;
use JsonException;
use RuntimeException;

class SqsPublisher
{
    /**
     * SendMessageBatch accepts at most ten entries per request.
     */
    private const int MAX_ENTRIES = 10;

    private SqsClient $client;

    public function __construct(
        private readonly string $queueUrl,
        array $config,
    ) {
        $this->client = new SqsClient([
            'endpoint' => $config['endpoint'],
            'region' => $config['region'],
            'version' => 'latest',
            'credentials' => [
                'key' => $config['key'],
                'secret' => $config['secret'],
            ],
        ]);
    }

    /**
     * Publishes each payload as its own message, grouping them into as few
     * SendMessageBatch requests as SQS allows.
     *
     * @param array<int, array> $payloads
     * @throws JsonException
     */
    public function publish(array $payloads): void
    {
        foreach (array_chunk($payloads, self::MAX_ENTRIES) as $chunk) {
            $this->send($chunk);
        }
    }

    /**
     * @param array<int, array> $payloads
     * @throws JsonException
     */
    private function send(array $payloads): void
    {
        $entries = [];

        foreach ($payloads as $index => $payload) {
            $entries[] = [
                'Id' => (string) $index,
                'MessageBody' => json_encode($payload, JSON_THROW_ON_ERROR),
            ];
        }

        $result = $this->client->sendMessageBatch([
            'QueueUrl' => $this->queueUrl,
            'Entries' => $entries,
        ]);

        $failed = $result->get('Failed') ?? [];

        if (count($failed) > 0) {
            throw new RuntimeException(
                'Failed to publish ' . count($failed) . ' message(s): '
                . implode(', ', array_column($failed, 'Message'))
            );
        }
    }
}
