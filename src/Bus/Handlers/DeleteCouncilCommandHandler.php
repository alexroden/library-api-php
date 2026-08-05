<?php

namespace AlexRoden\LibraryApiPhp\Bus\Handlers;

use AlexRoden\LibraryApiPhp\Bus\CommandHandler;
use AlexRoden\LibraryApiPhp\Bus\Commands\DeleteCouncilCommand;
use AlexRoden\LibraryApiPhp\Bus\Events\DeleteCouncilEvent;

class DeleteCouncilCommandHandler extends AbstractCommandHandler implements CommandHandler
{
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