<?php

namespace AlexRoden\LibraryApiPhp\Bus\Handlers;

use AlexRoden\LibraryApiPhp\Bus\CommandHandler;
use AlexRoden\LibraryApiPhp\Bus\Commands\CreateCouncilCommand;
use AlexRoden\LibraryApiPhp\Bus\Events\CreateCouncilEvent;
use AlexRoden\LibraryApiPhp\Exceptions\UndefinedClassException;
use AlexRoden\LibraryApiPhp\Models\Council;

class CreateCouncilCommandHandler extends AbstractCommandHandler implements CommandHandler
{
    /**
     * @throws UndefinedClassException
     */
    public function handle(object $command): Council
    {
        /** @var CreateCouncilCommand $command */
        $council = Council::create([
            'name' => $command->name,
        ]);

        $this->events->dispatch(
            new CreateCouncilEvent($council)
        );

        return $council;
    }
}
