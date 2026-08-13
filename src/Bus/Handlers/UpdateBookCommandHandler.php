<?php

namespace AlexRoden\LibraryApiPhp\Bus\Handlers;

use AlexRoden\LibraryApiPhp\Bus\CommandHandler;
use AlexRoden\LibraryApiPhp\Bus\Commands\UpdateBookCommand;
use AlexRoden\LibraryApiPhp\Bus\Events\CreateBookEvent;
use AlexRoden\LibraryApiPhp\Bus\Events\UpdateBookEvent;
use AlexRoden\LibraryApiPhp\Exceptions\UndefinedClassException;
use AlexRoden\LibraryApiPhp\Models\Book;

class UpdateBookCommandHandler extends AbstractCommandHandler implements CommandHandler
{
    /**
     * @throws UndefinedClassException
     */
    public function handle(object $command): Book
    {
        /** @var UpdateBookCommand $command */
        $command->book->update([
            'title' => $command->title,
            'description' => $command->description,
            'tags' => implode(',', $command->tags),
            'published_at' => $command->publishedAt,
        ]);

        $book = $command->book->refresh();

        $this->events->dispatch(
            new UpdateBookEvent($book)
        );

        return $book;
    }
}