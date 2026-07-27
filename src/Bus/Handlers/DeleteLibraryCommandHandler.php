<?php

namespace AlexRoden\LibraryApiPhp\Bus\Handlers;

use AlexRoden\LibraryApiPhp\Bus\CommandHandler;
use AlexRoden\LibraryApiPhp\Bus\Commands\DeleteLibraryCommand;
use AlexRoden\LibraryApiPhp\Bus\Events\DeleteLibraryEvent;
use AlexRoden\LibraryApiPhp\Exceptions\ResourceNotFoundException;
use AlexRoden\LibraryApiPhp\Exceptions\UndefinedClassException;

class DeleteLibraryCommandHandler extends AbstractCommandHandler implements CommandHandler
{
    public function handle(object $command): null
    {
        /** @var DeleteLibraryCommand $command */
        $library = $command->library;

        $library->delete();

        $this->events->dispatch(
            new DeleteLibraryEvent($library)
        );

        return null;
    }
}