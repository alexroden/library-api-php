<?php

namespace AlexRoden\LibraryApiPhp\Bus\Handlers;

use AlexRoden\LibraryApiPhp\Bus\CommandHandler;
use AlexRoden\LibraryApiPhp\Bus\Commands\DeleteStockCommand;
use AlexRoden\LibraryApiPhp\Bus\Events\DeleteStockEvent;

class DeleteStockCommandHandler extends AbstractCommandHandler implements CommandHandler
{
    public function handle(object $command): null
    {
        /** @var DeleteStockCommand $command */
        $stock = $command->stock;

        $stock->delete();

        $this->events->dispatch(
            new DeleteStockEvent($stock)
        );

        return null;
    }
}
