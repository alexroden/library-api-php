<?php

namespace AlexRoden\LibraryApiPhp\Bus\Commands;

readonly class CreateBookCommand
{
    public function __construct(
        public string $title,
        public string $description,
        public array $tags = [],
        public array $authors = [],
        public ?string $publishedAt = null,
    ) {}
}