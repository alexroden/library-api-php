<?php

namespace AlexRoden\LibraryApiPhp\Bus\Events;

use AlexRoden\LibraryApiPhp\Models\Author;

final readonly class DeleteAuthorEvent
{
    public function __construct(
        public Author $author,
    ) {
    }
}