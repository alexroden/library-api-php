<?php

namespace AlexRoden\LibraryApiPhp\Bus\Commands;

readonly class CreateAuthorCommand
{
    public function __construct(
        public string $firstName,
        public string $lastName,
    ) {}
}
