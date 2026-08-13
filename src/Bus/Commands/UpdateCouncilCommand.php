<?php

namespace AlexRoden\LibraryApiPhp\Bus\Commands;

use AlexRoden\LibraryApiPhp\Models\Council;

readonly class UpdateCouncilCommand
{
    public function __construct(
        public Council $council,
        public string $name,
    ) {}
}
