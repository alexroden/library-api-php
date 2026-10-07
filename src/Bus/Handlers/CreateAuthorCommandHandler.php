<?php

namespace AlexRoden\LibraryApiPhp\Bus\Handlers;

use AlexRoden\LibraryApiPhp\Bus\CommandHandler;
use AlexRoden\LibraryApiPhp\Bus\Commands\CreateAuthorCommand;
use AlexRoden\LibraryApiPhp\Bus\Events\CreateAuthorEvent;
use AlexRoden\LibraryApiPhp\Exceptions\UndefinedClassException;
use AlexRoden\LibraryApiPhp\Models\Author;

class CreateAuthorCommandHandler extends AbstractCommandHandler implements CommandHandler
{
    /**
     * @throws UndefinedClassException
     */
    public function handle(object $command): Author
    {
        /** @var CreateAuthorCommand $command */
        $author = Author::create([
            'first_name' => $command->firstName,
            'last_name' => $command->lastName,
        ]);


        $this->events->dispatch(
            new CreateAuthorEvent($author)
        );

        return $author;
    }
}
