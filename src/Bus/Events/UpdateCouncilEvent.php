<?php

namespace AlexRoden\LibraryApiPhp\Bus\Events;

use AlexRoden\LibraryApiPhp\Models\Council;

final readonly class UpdateCouncilEvent
{
    public function __construct(
        public Council $council,
    ) {
    }
}