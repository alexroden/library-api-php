<?php

namespace AlexRoden\LibraryApiPhp\Bus\Commands;

use AlexRoden\LibraryApiPhp\Models\Author;

readonly class DeleteAuthorCommand
{
    public function __construct(
        public Author $author,
    ) {}
}