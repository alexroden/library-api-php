<?php

namespace AlexRoden\LibraryApiPhp\Bus\Handlers;

use AlexRoden\LibraryApiPhp\Bus\CommandHandler;
use AlexRoden\LibraryApiPhp\Bus\Commands\DeleteCouncilCommand;
use AlexRoden\LibraryApiPhp\Bus\Events\DeleteCouncilEvent;
use AlexRoden\LibraryApiPhp\Exceptions\ResourceNotFoundException;
use AlexRoden\LibraryApiPhp\Exceptions\UndefinedClassException;

class DeleteCouncilCommandHandler extends AbstractCommandHandler implements CommandHandler
{
    /**
     * @throws UndefinedClassException|ResourceNotFoundException
     */
    public function handle(object $command): null
    {
        /** @var DeleteCouncilCommand $command */
        $council = $command->council;

        $council->delete();

        $this->events->dispatch(
            new DeleteCouncilEvent($council)
        );

        return null;
    }
}