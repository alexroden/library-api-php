<?php

namespace AlexRoden\LibraryApiPhp\Bus\Events;

use AlexRoden\LibraryApiPhp\Models\User;

final readonly class DeleteUserEvent
{
    public function __construct(
        public User $user,
    ) {
    }
}