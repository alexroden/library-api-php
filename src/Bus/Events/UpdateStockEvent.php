<?php

namespace AlexRoden\LibraryApiPhp\Bus\Events;

use AlexRoden\LibraryApiPhp\Models\Stock;

final readonly class UpdateStockEvent
{
    public function __construct(
        public Stock $stock,
    ) {
    }
}
