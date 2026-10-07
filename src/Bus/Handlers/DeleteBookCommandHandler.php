<?php

namespace AlexRoden\LibraryApiPhp\Bus\Handlers;

use AlexRoden\LibraryApiPhp\Bus\CommandHandler;
use AlexRoden\LibraryApiPhp\Bus\Commands\DeleteBookCommand;
use AlexRoden\LibraryApiPhp\Bus\Events\DeleteBookEvent;

class DeleteBookCommandHandler extends AbstractCommandHandler implements CommandHandler
{
    public function handle(object $command): null
    {
        /** @var DeleteBookCommand $command */
        $book = $command->book;

        $book->delete();

        $this->events->dispatch(
            new DeleteBookEvent($book)
        );

        return null;
    }
}
