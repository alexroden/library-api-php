<?php

namespace AlexRoden\LibraryApiPhp\Bus\Handlers;

use AlexRoden\LibraryApiPhp\Bus\CommandHandler;
use AlexRoden\LibraryApiPhp\Bus\Commands\CreateBookCommand;
use AlexRoden\LibraryApiPhp\Bus\Events\CreateBookEvent;
use AlexRoden\LibraryApiPhp\Exceptions\UndefinedClassException;
use AlexRoden\LibraryApiPhp\Models\Book;

class CreateBookCommandHandler extends AbstractCommandHandler implements CommandHandler
{
    /**
     * @throws UndefinedClassException
     */
    public function handle(object $command): Book
    {
        /** @var CreateBookCommand $command */
        $book = Book::create([
            'title' => $command->title,
            'description' => $command->description,
            'tags' => implode(',', $command->tags),
        ]);

        if (count($command->authors) > 0) {
            foreach ($command->authors as $author) {
                $book->assignAuthor($author);
            }
        }

        $this->events->dispatch(
            new CreateBookEvent($book)
        );

        return $book;
    }
}