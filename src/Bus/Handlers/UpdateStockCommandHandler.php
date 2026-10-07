<?php

namespace AlexRoden\LibraryApiPhp\Bus\Handlers;

use AlexRoden\LibraryApiPhp\Bus\CommandHandler;
use AlexRoden\LibraryApiPhp\Bus\Commands\UpdateStockCommand;
use AlexRoden\LibraryApiPhp\Bus\Events\UpdateStockEvent;
use AlexRoden\LibraryApiPhp\Exceptions\UndefinedClassException;
use AlexRoden\LibraryApiPhp\Models\Stock;

class UpdateStockCommandHandler extends AbstractCommandHandler implements CommandHandler
{
    /**
     * @throws UndefinedClassException
     */
    public function handle(object $command): Stock
    {
        /** @var UpdateStockCommand $command */
        $command->stock->update([
            'quantity' => $command->quantity,
        ]);

        $stock = $command->stock->refresh();

        $this->events->dispatch(
            new UpdateStockEvent($stock)
        );

        return $stock;
    }
}
