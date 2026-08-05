<?php

namespace AlexRoden\LibraryApiPhp\Bus\Commands;

use AlexRoden\LibraryApiPhp\Models\Book;

readonly class UpdateBookCommand
{
    public function __construct(
        public Book $book,
        public string $title,
        public string $description,
        public array $tags = [],
        public array $authors = [],
    ) {}
}