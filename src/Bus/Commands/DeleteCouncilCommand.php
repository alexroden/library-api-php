<?php

namespace AlexRoden\LibraryApiPhp\Bus\Commands;

use AlexRoden\LibraryApiPhp\Models\Council;

readonly class DeleteCouncilCommand
{
    public function __construct(
        public Council $council,
    ) {}
}
