<?php

namespace AlexRoden\LibraryApiPhp\Bus\Commands;

readonly class CreateLibraryCommand
{
    public function __construct(
        public string $name,
    ) {}
}