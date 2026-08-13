<?php

namespace AlexRoden\Importers\Queue;

use JsonException;
use UnexpectedValueException;

/**
 * A single message read from the queue, held alongside its receipt handle so
 * it can be deleted once everything inside it has been processed.
 */
readonly class Message
{
    public function __construct(
        public string $id,
        public string $receiptHandle,
        public array $body,
    ) {}

    /**
     * @throws JsonException
     */
    public static function fromResult(array $message): self
    {
        $body = json_decode(
            $message['Body'] ?? '',
            true,
            512,
            JSON_THROW_ON_ERROR,
        );

        if (!is_array($body)) {
            throw new UnexpectedValueException(
                'Queue message body is not a JSON object.'
            );
        }

        return new self(
            (string) $message['MessageId'],
            (string) $message['ReceiptHandle'],
            $body,
        );
    }
}
