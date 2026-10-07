<?php

namespace AlexRoden\LibraryApiPhp\Bus\Commands;

use AlexRoden\LibraryApiPhp\Models\Library;

readonly class UpdateLibraryCommand
{
    public function __construct(
        public Library $library,
        public string $name,
    ) {}
}
