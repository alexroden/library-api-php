<?php

namespace AlexRoden\LibraryApiPhp\Bus\Events;

use AlexRoden\LibraryApiPhp\Models\Author;

final readonly class UpdateAuthorEvent
{
    public function __construct(
        public Author $author,
    ) {
    }
}
