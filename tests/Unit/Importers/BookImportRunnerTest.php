<?php

namespace AlexRoden\LibraryApiPhp\Tests\Unit\Importers;

use AlexRoden\Importers\Queue\SqsPublisher;
use AlexRoden\Importers\Runner\BookImportRunner;
use AlexRoden\Importers\Soap\BookClient;
use AlexRoden\Importers\Soap\BookSummary;
use PHPUnit\Framework\TestCase;

class BookImportRunnerTest extends TestCase
{
    public function testPublishesBookIdsInBatches(): void
    {
        $client = $this->createStub(BookClient::class);
        $client->method('getBooks')->willReturn([
            new BookSummary(1, 'The Secret Embers', 'Elias Langley'),
            new BookSummary(2, 'A Map of Wolves', 'Nadia Fenwick'),
            new BookSummary(3, 'The Glass Orchard', 'Rowan Ashcroft'),
        ]);

        $publisher = $this->createMock(SqsPublisher::class);
        $publisher->expects($this->once())
            ->method('publish')
            ->with([
                ['ids' => [1, 2]],
                ['ids' => [3]],
            ]);

        $this->runSilently(new BookImportRunner($client, $publisher, 2));
    }

    public function testPublishesNothingWhenThereAreNoBooks(): void
    {
        $client = $this->createStub(BookClient::class);
        $client->method('getBooks')->willReturn([]);

        $publisher = $this->createMock(SqsPublisher::class);
        $publisher->expects($this->never())->method('publish');

        $this->runSilently(new BookImportRunner($client, $publisher, 25));
    }

    /**
     * The runner reports progress on stdout, which is not what is under test.
     */
    private function runSilently(BookImportRunner $runner): void
    {
        ob_start();

        try {
            $runner->run();
        } finally {
            ob_end_clean();
        }
    }
}
