<?php

namespace AlexRoden\LibraryApiPhp\Bus\Commands;

use AlexRoden\LibraryApiPhp\Models\Book;

readonly class DeleteBookCommand
{
    public function __construct(
        public Book $book,
    ) {}
}
