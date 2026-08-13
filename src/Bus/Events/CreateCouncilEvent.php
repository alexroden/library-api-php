<?php

namespace AlexRoden\LibraryApiPhp\Bus\Events;

use AlexRoden\LibraryApiPhp\Models\Council;

final readonly class CreateCouncilEvent
{
    public function __construct(
        public Council $council,
    ) {
    }
}
