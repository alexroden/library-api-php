<?php

namespace AlexRoden\LibraryApiPhp\Bus\Events;

use AlexRoden\LibraryApiPhp\Models\Stock;

final readonly class DeleteStockEvent
{
    public function __construct(
        public Stock $stock,
    ) {
    }
}
