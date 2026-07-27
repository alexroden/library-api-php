<?php

namespace AlexRoden\LibraryApiPhp\Bus\Handlers;

use AlexRoden\LibraryApiPhp\Bus\CommandHandler;
use AlexRoden\LibraryApiPhp\Bus\Commands\UpdateAuthorCommand;
use AlexRoden\LibraryApiPhp\Bus\Events\UpdateAuthorEvent;
use AlexRoden\LibraryApiPhp\Exceptions\UndefinedClassException;
use AlexRoden\LibraryApiPhp\Models\Author;

class UpdateAuthorCommandHandler extends AbstractCommandHandler implements CommandHandler
{
    /**
     * @throws UndefinedClassException
     */
    public function handle(object $command): Author
    {
        /** @var UpdateAuthorCommand $command */
        $command->author->update([
            'first_name' => $command->firstName,
            'last_name' => $command->lastName,
        ]);

        $author = $command->author->refresh();

        $this->events->dispatch(
            new UpdateAuthorEvent($author)
        );

        return $author;
    }
}