<?php

namespace AlexRoden\LibraryApiPhp\Bus\Commands;

readonly class CreateCategoryCommand
{
    public function __construct(
        public string $name,
    ) {}
}
