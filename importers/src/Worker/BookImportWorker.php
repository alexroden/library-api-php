<?php

namespace AlexRoden\Importers\Worker;

use AlexRoden\Importers\Queue\Message;
use AlexRoden\Importers\Queue\SqsConsumer;
use AlexRoden\Importers\Soap\AuthorName;
use AlexRoden\Importers\Soap\BookClient;
use AlexRoden\Importers\Soap\BookDetail;
use AlexRoden\LibraryApiPhp\Bus\CommandBus;
use AlexRoden\LibraryApiPhp\Bus\Commands\CreateAuthorCommand;
use AlexRoden\LibraryApiPhp\Bus\Commands\CreateBookCommand;
use AlexRoden\LibraryApiPhp\Bus\Commands\CreateCategoryCommand;
use AlexRoden\LibraryApiPhp\Models\Author;
use AlexRoden\LibraryApiPhp\Models\Book;
use AlexRoden\LibraryApiPhp\Models\Category;
use Throwable;

/**
 * Reads batches of book ids from the queue and pulls the full record for each
 * one from the api. The runner only queues ids, so this is where the slower
 * per-book getBook calls and all of the writing happen.
 *
 * Records are written through the command bus, so an imported book takes the
 * same path as one created through the api and raises the same events. Every
 * lookup is a first-or-create, which is what makes a batch safe to retry.
 *
 * The worker is a long lived process: an empty queue means there is nothing to
 * do *yet*, not that the work is finished, so it keeps polling until it is
 * asked to stop.
 */
class BookImportWorker
{
    private bool $running = false;

    public function __construct(
        private readonly BookClient   $client,
        private readonly SqsConsumer  $consumer,
        private readonly CommandBus   $commands,
    ) {}

    public function run(): void
    {
        echo "Waiting for book batches...\n";

        $this->running = true;
        $idle = false;

        while ($this->running) {
            $messages = $this->consumer->receive();

            /*
             * receive() long polls, so an empty response has already cost the
             * consumer's wait time and looping again is not a busy spin. The
             * message is only printed on the way into idle to keep a worker
             * that is waiting for hours quiet.
             */
            if (count($messages) === 0) {
                if (!$idle) {
                    echo "Queue is empty, waiting for more work...\n";

                    $idle = true;
                }

                continue;
            }

            $idle = false;

            foreach ($messages as $message) {
                $this->handle($message);

                /*
                 * A stop that arrives mid batch still finishes the message it
                 * interrupted, but does not start another one.
                 */
                if (!$this->running) {
                    break;
                }
            }
        }

        echo "Worker stopped.\n";
    }

    /**
     * Asks the worker to finish what it is doing and return from run(). Called
     * from the signal handlers in main.php so that a `docker stop` does not
     * kill the process part way through a batch.
     */
    public function stop(): void
    {
        $this->running = false;
    }

    private function handle(Message $message): void
    {
        $ids = array_map(intval(...), $message->body['ids'] ?? []);

        if (count($ids) === 0) {
            echo "Batch {$message->id} contained no ids, discarding it.\n";

            $this->consumer->delete($message);

            return;
        }

        echo 'Importing ' . count($ids) . " book(s)...\n";

        $failed = [];
        foreach ($ids as $id) {
            try {
                $this->import($id);
            } catch (Throwable $e) {
                $failed[] = $id;

                echo "Failed to import book {$id}: {$e->getMessage()}\n";
            }
        }

        /*
         * The batch is only deleted once every id in it landed. Anything left
         * behind becomes visible again after the visibility timeout and is
         * retried in full; the books that already succeeded are picked up by
         * the first-or-create lookups rather than duplicated.
         */
        if (count($failed) > 0) {
            echo count($failed) . " book(s) failed [" . implode(', ', $failed)
                . "], leaving batch {$message->id} on the queue to retry.\n";

            return;
        }

        $this->consumer->delete($message);

        echo 'Imported ' . count($ids) . " book(s).\n";
    }

    private function import(int $id): void
    {
        $detail = $this->client->getBook($id);

        $author = $this->author($detail->author);
        $category = $this->category($detail->category);

        $book = $this->book($detail, $author);

        /*
         * Creating the book already links the author. Assigning both here as
         * well is what repairs a book that was imported before, and both
         * methods ignore a link that is already there.
         */
        $book->assignAuthor($author);
        $book->assignCategory($category);
    }

    private function author(string $author): Author
    {
        $name = AuthorName::fromString($author);

        $existing = Author::where('first_name', '=', $name->firstName)
            ->where('last_name', '=', $name->lastName)
            ->first();

        if ($existing !== null) {
            return $existing;
        }

        return $this->commands->dispatch(
            new CreateAuthorCommand(
                $name->firstName,
                $name->lastName,
            )
        );
    }

    private function book(BookDetail $detail, Author $author): Book
    {
        $existing = Book::where('title', '=', $detail->title)->first();

        if ($existing !== null) {
            return $existing;
        }

        return $this->commands->dispatch(
            new CreateBookCommand(
                title: $detail->title,
                description: $detail->description,
                tags: $detail->tags,
                authors: [$author->id],
            )
        );
    }

    private function category(string $category): Category
    {
        $existing = Category::where('name', '=', $category)->first();

        if ($existing !== null) {
            return $existing;
        }

        return $this->commands->dispatch(
            new CreateCategoryCommand($category)
        );
    }
}
