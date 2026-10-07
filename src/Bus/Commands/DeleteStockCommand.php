<?php

namespace AlexRoden\LibraryApiPhp\Bus\Commands;

use AlexRoden\LibraryApiPhp\Models\Stock;

readonly class DeleteStockCommand
{
    public function __construct(
        public Stock $stock,
    ) {}
}
