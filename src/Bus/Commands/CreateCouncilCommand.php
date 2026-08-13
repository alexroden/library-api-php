<?php

namespace AlexRoden\LibraryApiPhp\Bus\Commands;

readonly class CreateCouncilCommand
{
    public function __construct(
        public string $name,
    ) {}
}
