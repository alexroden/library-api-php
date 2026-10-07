<?php

namespace AlexRoden\LibraryApiPhp\Bus\Handlers;

use AlexRoden\LibraryApiPhp\Bus\CommandHandler;
use AlexRoden\LibraryApiPhp\Bus\Commands\UpdateCouncilCommand;
use AlexRoden\LibraryApiPhp\Bus\Commands\UpdateLibraryCommand;
use AlexRoden\LibraryApiPhp\Bus\Events\UpdateCouncilEvent;
use AlexRoden\LibraryApiPhp\Bus\Events\UpdateLibraryEvent;
use AlexRoden\LibraryApiPhp\Exceptions\UndefinedClassException;
use AlexRoden\LibraryApiPhp\Models\Council;
use AlexRoden\LibraryApiPhp\Models\Library;

class UpdateLibraryCommandHandler extends AbstractCommandHandler implements CommandHandler
{
    /**
     * @throws UndefinedClassException
     */
    public function handle(object $command): Library
    {
        /** @var UpdateLibraryCommand $command */
        $command->library->update([
            'name' => $command->name,
        ]);

        $library = $command->library->refresh();

        $this->events->dispatch(
            new UpdateLibraryEvent($library)
        );

        return $library;
    }
}
