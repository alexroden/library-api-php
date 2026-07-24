<?php

namespace AlexRoden\LibraryApiPhp\Bus\Handlers;

use AlexRoden\LibraryApiPhp\Bus\CommandHandler;
use AlexRoden\LibraryApiPhp\Bus\Commands\UpdateCouncilCommand;
use AlexRoden\LibraryApiPhp\Bus\Events\UpdateCouncilEvent;
use AlexRoden\LibraryApiPhp\Exceptions\UndefinedClassException;
use AlexRoden\LibraryApiPhp\Models\Council;

class UpdateCouncilCommandHandler extends AbstractCommandHandler implements CommandHandler
{
    /**
     * @throws UndefinedClassException
     */
    public function handle(object $command): Council
    {
        /** @var UpdateCouncilCommand $command */
        $command->council->update([
            'name' => $command->name,
        ]);

        $council = $command->council->refresh();

        $this->events->dispatch(
            new UpdateCouncilEvent($council)
        );

        return $council;
    }
}