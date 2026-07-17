<?php

namespace AlexRoden\LibraryApiPhp\Bus\Events;

use AlexRoden\LibraryApiPhp\Models\User;

final class CreateUserEvent
{
    public function __construct(
        public readonly User $user,
    ) {}
}