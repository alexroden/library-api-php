<?php

namespace AlexRoden\LibraryApiPhp\Bus\Handlers;

use AlexRoden\LibraryApiPhp\Bus\CommandHandler;
use AlexRoden\LibraryApiPhp\Bus\Commands\CreateLibraryCommand;
use AlexRoden\LibraryApiPhp\Bus\Events\CreateLibraryEvent;
use AlexRoden\LibraryApiPhp\Exceptions\UndefinedClassException;
use AlexRoden\LibraryApiPhp\Models\Library;

class CreateLibraryCommandHandler extends AbstractCommandHandler implements CommandHandler
{
    /**
     * @throws UndefinedClassException
     */
    public function handle(object $command): Library
    {
        /** @var CreateLibraryCommand $command */
        $library = Library::create([
            'name' => $command->name,
        ]);

        $this->events->dispatch(
            new CreateLibraryEvent($library)
        );

        return $library;
    }
}
