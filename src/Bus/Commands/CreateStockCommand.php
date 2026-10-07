<?php

namespace AlexRoden\LibraryApiPhp\Bus\Commands;

readonly class CreateStockCommand
{
    public function __construct(
        public int $libraryId,
        public int $bookId,
        public int $quantity = 0,
    ) {}
}
