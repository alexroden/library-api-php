<?php

namespace AlexRoden\LibraryApiPhp\Bus\Events;

use AlexRoden\LibraryApiPhp\Models\Book;

final readonly class UpdateBookEvent
{
    public function __construct(
        public Book $book,
    ) {
    }
}