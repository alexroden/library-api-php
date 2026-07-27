<?php

namespace AlexRoden\LibraryApiPhp\Bus\Commands;

use AlexRoden\LibraryApiPhp\Models\Author;

readonly class UpdateAuthorCommand
{
    public function __construct(
        public Author $author,
        public string $firstName,
        public string $lastName,
    ) {}
}