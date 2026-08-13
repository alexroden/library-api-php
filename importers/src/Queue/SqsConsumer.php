<?php

namespace AlexRoden\Importers\Queue;

use Aws\Sqs\SqsClient;
use JsonException;

class SqsConsumer
{
    /**
     * ReceiveMessage returns at most ten messages per request.
     */
    private const int MAX_MESSAGES = 10;

    /**
     * Long poll rather than spin, so an empty response is a reliable signal
     * that the queue really has been drained.
     */
    private const int WAIT_TIME = 10;

    /**
     * How long a batch stays hidden from other workers while it is being
     * imported. A batch that is not deleted becomes visible again once this
     * expires, which is what gives failed books another attempt.
     */
    private const int VISIBILITY_TIMEOUT = 60;

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
     * @return Message[]
     *
     * @throws JsonException
     */
    public function receive(): array
    {
        $result = $this->client->receiveMessage([
            'QueueUrl' => $this->queueUrl,
            'MaxNumberOfMessages' => self::MAX_MESSAGES,
            'WaitTimeSeconds' => self::WAIT_TIME,
            'VisibilityTimeout' => self::VISIBILITY_TIMEOUT,
        ]);

        return array_map(
            static fn (array $message): Message => Message::fromResult($message),
            $result->get('Messages') ?? []
        );
    }

    public function delete(Message $message): void
    {
        $this->client->deleteMessage([
            'QueueUrl' => $this->queueUrl,
            'ReceiptHandle' => $message->receiptHandle,
        ]);
    }
}
