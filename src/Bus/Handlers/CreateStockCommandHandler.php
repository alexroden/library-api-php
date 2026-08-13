<?php

namespace AlexRoden\LibraryApiPhp\Bus\Handlers;

use AlexRoden\LibraryApiPhp\Bus\CommandHandler;
use AlexRoden\LibraryApiPhp\Bus\Commands\CreateStockCommand;
use AlexRoden\LibraryApiPhp\Bus\Events\CreateStockEvent;
use AlexRoden\LibraryApiPhp\Exceptions\UndefinedClassException;
use AlexRoden\LibraryApiPhp\Models\Stock;

class CreateStockCommandHandler extends AbstractCommandHandler implements CommandHandler
{
    /**
     * @throws UndefinedClassException
     */
    public function handle(object $command): Stock
    {
        /** @var CreateStockCommand $command */
        $stock = Stock::create([
            'library_id' => $command->libraryId,
            'book_id' => $command->bookId,
            'quantity' => $command->quantity,
        ]);

        $this->events->dispatch(
            new CreateStockEvent($stock)
        );

        return $stock;
    }
}
