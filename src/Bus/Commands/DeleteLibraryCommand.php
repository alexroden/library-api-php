<?php

namespace AlexRoden\LibraryApiPhp\Bus\Commands;

use AlexRoden\LibraryApiPhp\Models\Library;

readonly class DeleteLibraryCommand
{
    public function __construct(
        public Library $library,
    ) {}
}
