<?php

namespace AlexRoden\LibraryApiPhp\Bus\Handlers;

use AlexRoden\LibraryApiPhp\Bus\CommandHandler;
use AlexRoden\LibraryApiPhp\Bus\Commands\DeleteAuthorCommand;
use AlexRoden\LibraryApiPhp\Bus\Events\DeleteAuthorEvent;

class DeleteAuthorCommandHandler extends AbstractCommandHandler implements CommandHandler
{
    public function handle(object $command): null
    {
        /** @var DeleteAuthorCommand $command */
        $author = $command->author;

        $author->delete();

        $this->events->dispatch(
            new DeleteAuthorEvent($author)
        );

        return null;
    }
}