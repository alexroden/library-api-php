<?php

namespace AlexRoden\LibraryApiPhp\Bus\Events;

use AlexRoden\LibraryApiPhp\Models\Library;

final readonly class UpdateLibraryEvent
{
    public function __construct(
        public Library $library,
    ) {
    }
}