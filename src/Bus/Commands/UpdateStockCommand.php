<?php

namespace AlexRoden\LibraryApiPhp\Bus\Commands;

use AlexRoden\LibraryApiPhp\Models\Stock;

readonly class UpdateStockCommand
{
    public function __construct(
        public Stock $stock,
        public int $quantity = 0,
    ) {}
}
