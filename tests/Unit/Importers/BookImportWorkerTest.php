<?php

namespace AlexRoden\LibraryApiPhp\Tests\Unit\Importers;

use AlexRoden\Importers\Queue\Message;
use AlexRoden\Importers\Queue\SqsConsumer;
use AlexRoden\Importers\Soap\BookClient;
use AlexRoden\Importers\Soap\BookDetail;
use AlexRoden\Importers\Worker\BookImportWorker;
use AlexRoden\LibraryApiPhp\Bus\CommandBus;
use AlexRoden\LibraryApiPhp\Bus\Commands\CreateAuthorCommand;
use AlexRoden\LibraryApiPhp\Bus\Commands\CreateBookCommand;
use AlexRoden\LibraryApiPhp\Bus\Commands\CreateCategoryCommand;
use AlexRoden\LibraryApiPhp\Bus\EventBus;
use AlexRoden\LibraryApiPhp\Bus\Handlers\CreateAuthorCommandHandler;
use AlexRoden\LibraryApiPhp\Bus\Handlers\CreateBookCommandHandler;
use AlexRoden\LibraryApiPhp\Bus\Handlers\CreateCategoryCommandHandler;
use AlexRoden\LibraryApiPhp\Models\Author;
use AlexRoden\LibraryApiPhp\Models\Book;
use AlexRoden\LibraryApiPhp\Models\Category;
use AlexRoden\LibraryApiPhp\Tests\AbstractTestCase;
use AlexRoden\LibraryApiPhp\Tests\Factories\AuthorFactory;
use AlexRoden\LibraryApiPhp\Tests\Factories\BookFactory;
use AlexRoden\LibraryApiPhp\Tests\Factories\CategoryFactory;
use PHPUnit\Framework\MockObject\Stub;
use RuntimeException;

class BookImportWorkerTest extends AbstractTestCase
{
    public function testImportsEveryBookInTheBatch(): void
    {
        $client = $this->client([
            1 => $this->detail(1, 'The Secret Embers', 'Elias Langley', 'fantasy'),
            2 => $this->detail(2, 'A Map of Wolves', 'Lydia Blackwood', 'adventure'),
        ]);

        $message = new Message('batch-1', 'receipt-1', ['ids' => [1, 2]]);

        $consumer = $this->createMock(SqsConsumer::class);
        $consumer->method('receive')->willReturnOnConsecutiveCalls([$message], []);
        $consumer->expects($this->once())->method('delete')->with($message);

        $this->work($client, $consumer);

        $this->assertCount(2, Book::get());
        $this->assertCount(2, Author::get());
        $this->assertCount(2, Category::get());

        $book = Book::where('title', '=', 'The Secret Embers')->first();
        $this->assertNotNull($book);
        $this->assertEquals('The Secret Embers is a book.', $book->description);
        $this->assertEquals(['magic', 'quest'], $book->tags());

        $authors = $book->authors();
        $this->assertCount(1, $authors);
        $this->assertEquals('Elias', $authors[0]->first_name);
        $this->assertEquals('Langley', $authors[0]->last_name);

        $categories = $book->categories();
        $this->assertCount(1, $categories);
        $this->assertEquals('fantasy', $categories[0]->name);
    }

    public function testSplitsAMultiWordLastName(): void
    {
        $client = $this->client([
            1 => $this->detail(1, 'The Glass Orchard', 'Jean de la Fontaine', 'drama'),
        ]);

        $this->work($client, $this->consumer(
            new Message('batch-1', 'receipt-1', ['ids' => [1]])
        ));

        $author = Author::where('last_name', '=', 'de la Fontaine')->first();
        $this->assertNotNull($author);
        $this->assertEquals('Jean', $author->first_name);
    }

    public function testReusesTheAuthorAndCategoryAcrossBooks(): void
    {
        $client = $this->client([
            1 => $this->detail(1, 'The Secret Embers', 'Elias Langley', 'fantasy'),
            2 => $this->detail(2, 'The Secret Dragons', 'Elias Langley', 'fantasy'),
        ]);

        $this->work($client, $this->consumer(
            new Message('batch-1', 'receipt-1', ['ids' => [1, 2]])
        ));

        $this->assertCount(2, Book::get());
        $this->assertCount(1, Author::get());
        $this->assertCount(1, Category::get());
    }

    public function testReusesRecordsThatAlreadyExist(): void
    {
        $author = AuthorFactory::create([
            'first_name' => 'Elias',
            'last_name' => 'Langley',
        ]);
        CategoryFactory::create(['name' => 'fantasy']);
        BookFactory::create([
            'title' => 'The Secret Embers',
            'description' => 'Imported on a previous run.',
            'tags' => 'magic',
        ]);

        $client = $this->client([
            1 => $this->detail(1, 'The Secret Embers', 'Elias Langley', 'fantasy'),
        ]);

        $this->work($client, $this->consumer(
            new Message('batch-1', 'receipt-1', ['ids' => [1]])
        ));

        $this->assertCount(1, Book::get());
        $this->assertCount(1, Author::get());
        $this->assertCount(1, Category::get());

        $book = Book::where('title', '=', 'The Secret Embers')->first();

        /*
         * The existing book is reused rather than overwritten, but its links
         * are still filled in.
         */
        $this->assertEquals('Imported on a previous run.', $book->description);

        $this->assertEquals(
            [$author->id],
            array_map(static fn (Author $linked): int => $linked->id, $book->authors())
        );
        $this->assertEquals(
            ['fantasy'],
            array_map(static fn (Category $linked): string => $linked->name, $book->categories())
        );
    }

    public function testDoesNotLinkABookTwiceWhenTheBatchIsRetried(): void
    {
        $client = $this->client([
            1 => $this->detail(1, 'The Secret Embers', 'Elias Langley', 'fantasy'),
        ]);

        $message = new Message('batch-1', 'receipt-1', ['ids' => [1]]);

        $consumer = $this->createStub(SqsConsumer::class);
        $consumer->method('receive')->willReturnOnConsecutiveCalls(
            [$message],
            [$message],
            [],
        );

        $this->work($client, $consumer);

        $book = Book::where('title', '=', 'The Secret Embers')->first();
        $this->assertCount(1, $book->authors());
        $this->assertCount(1, $book->categories());
    }

    public function testKeepsTheBatchOnTheQueueWhenABookFails(): void
    {
        $client = $this->client([
            1 => $this->detail(1, 'The Secret Embers', 'Elias Langley', 'fantasy'),
        ]);

        $message = new Message('batch-1', 'receipt-1', ['ids' => [1, 99]]);

        $consumer = $this->createMock(SqsConsumer::class);
        $consumer->method('receive')->willReturnOnConsecutiveCalls([$message], []);
        $consumer->expects($this->never())->method('delete');

        $this->work($client, $consumer);

        /*
         * The book that did import is still written, so the retry finds it
         * rather than creating it a second time.
         */
        $this->assertCount(1, Book::get());
        $this->assertNotNull(Book::where('title', '=', 'The Secret Embers')->first());
    }

    public function testDiscardsABatchWithNoIds(): void
    {
        $client = $this->createMock(BookClient::class);
        $client->expects($this->never())->method('getBook');

        $message = new Message('batch-1', 'receipt-1', ['ids' => []]);

        $consumer = $this->createMock(SqsConsumer::class);
        $consumer->method('receive')->willReturnOnConsecutiveCalls([$message], []);
        $consumer->expects($this->once())->method('delete')->with($message);

        $this->work($client, $consumer);

        $this->assertCount(0, Book::get());
    }

    /**
     * An empty queue means there is no work yet, not that the worker is
     * finished, so it keeps polling until something asks it to stop.
     */
    public function testKeepsPollingWhileTheQueueIsEmpty(): void
    {
        $client = $this->createMock(BookClient::class);
        $client->expects($this->never())->method('getBook');

        $consumer = $this->createMock(SqsConsumer::class);
        $consumer->expects($this->never())->method('delete');

        $worker = new BookImportWorker($client, $consumer, new CommandBus());

        $polls = 0;
        $consumer->method('receive')->willReturnCallback(
            static function () use (&$polls, $worker): array {
                $polls++;

                if ($polls === 3) {
                    $worker->stop();
                }

                return [];
            }
        );

        $this->runQuietly($worker);

        $this->assertEquals(3, $polls);
    }

    /**
     * A stop that arrives while a batch is being handled still finishes that
     * message rather than dropping it half imported.
     */
    public function testFinishesTheCurrentBatchBeforeStopping(): void
    {
        $client = $this->client([
            1 => $this->detail(1, 'The Secret Embers', 'Elias Langley', 'fantasy'),
        ]);

        $message = new Message('batch-1', 'receipt-1', ['ids' => [1]]);

        $consumer = $this->createMock(SqsConsumer::class);
        $consumer->expects($this->once())->method('delete')->with($message);

        $worker = $this->worker($client, $consumer);

        $consumer->method('receive')->willReturnCallback(
            static function () use ($message, $worker): array {
                $worker->stop();

                return [$message];
            }
        );

        $this->runQuietly($worker);

        $this->assertCount(1, Book::get());
    }

    /**
     * An id that is not in the given map behaves like the api does for an
     * unknown book and throws.
     *
     * @param array<int, BookDetail> $details
     */
    private function client(array $details): BookClient&Stub
    {
        $client = $this->createStub(BookClient::class);
        $client->method('getBook')->willReturnCallback(
            static fn (int $id): BookDetail => $details[$id]
                ?? throw new RuntimeException("Book with ID {$id} not found")
        );

        return $client;
    }

    /**
     * A consumer that hands out the given message once and is empty after it.
     */
    private function consumer(Message $message): SqsConsumer&Stub
    {
        $consumer = $this->createStub(SqsConsumer::class);
        $consumer->method('receive')->willReturnOnConsecutiveCalls([$message], []);

        return $consumer;
    }

    private function detail(
        int $id,
        string $title,
        string $author,
        string $category,
    ): BookDetail {
        return new BookDetail(
            id: $id,
            title: $title,
            description: "{$title} is a book.",
            tags: ['magic', 'quest'],
            category: $category,
            author: $author,
        );
    }

    private function worker(BookClient $client, SqsConsumer $consumer): BookImportWorker
    {
        $events = new EventBus();

        $commands = new CommandBus();
        $commands->register(CreateAuthorCommand::class, new CreateAuthorCommandHandler($events));
        $commands->register(CreateBookCommand::class, new CreateBookCommandHandler($events));
        $commands->register(CreateCategoryCommand::class, new CreateCategoryCommandHandler($events));

        return new BookImportWorker($client, $consumer, $commands);
    }

    /**
     * The worker itself waits on an empty queue forever, so the tests that
     * drive a fixed set of batches wrap the consumer in one that stops the
     * worker as soon as the queue runs dry.
     */
    private function work(BookClient $client, SqsConsumer $consumer): void
    {
        $stopping = new class ($consumer) extends SqsConsumer {
            public BookImportWorker $worker;

            public function __construct(private readonly SqsConsumer $inner) {}

            public function receive(): array
            {
                $messages = $this->inner->receive();

                if (count($messages) === 0) {
                    $this->worker->stop();
                }

                return $messages;
            }

            public function delete(Message $message): void
            {
                $this->inner->delete($message);
            }
        };

        $worker = $this->worker($client, $stopping);
        $stopping->worker = $worker;

        $this->runQuietly($worker);
    }

    /**
     * The worker reports progress on stdout, which is not what is under test.
     */
    private function runQuietly(BookImportWorker $worker): void
    {
        ob_start();

        try {
            $worker->run();
        } finally {
            ob_end_clean();
        }
    }
}
