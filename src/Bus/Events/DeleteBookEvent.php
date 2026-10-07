<?php

namespace AlexRoden\LibraryApiPhp\Bus\Events;

use AlexRoden\LibraryApiPhp\Models\Book;

final readonly class DeleteBookEvent
{
    public function __construct(
        public Book $book,
    ) {
    }
}
