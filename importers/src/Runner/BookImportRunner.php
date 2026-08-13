<?php

namespace AlexRoden\Importers\Runner;

use AlexRoden\Importers\Queue\SqsPublisher;
use AlexRoden\Importers\Soap\BookClient;
use AlexRoden\Importers\Soap\BookSummary;
use JsonException;
use SoapFault;

/**
 * Reads the book list from the SOAP api and hands the ids off to the queue in
 * batches. The list method only returns a summary, so it is the worker that
 * calls getBook for the full record.
 */
readonly class BookImportRunner
{
    public function __construct(
        private BookClient   $client,
        private SqsPublisher $publisher,
        private int          $batchSize,
    ) {}

    /**
     * @throws SoapFault|JsonException
     */
    public function run(): void
    {
        echo "Fetching books...\n";

        $books = $this->client->getBooks();

        if (count($books) === 0) {
            echo "No books returned, nothing to queue.\n";

            return;
        }

        $batches = array_chunk(
            array_map(
                static fn (BookSummary $book): int => $book->id,
                $books
            ),
            $this->batchSize
        );

        echo 'Queueing ' . count($books) . ' book(s) in '
            . count($batches) . " batch(es) of up to {$this->batchSize}...\n";

        $this->publisher->publish(
            array_map(
                static fn (array $ids): array => ['ids' => $ids],
                $batches
            )
        );

        echo "Import queued.\n";
    }
}
