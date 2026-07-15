<?php

namespace AlexRoden\LibraryApiPhp\Enums;

use AlexRoden\LibraryApiPhp\Enums\Concerns\ConstantsTrait;

final class Roles
{
    use ConstantsTrait;

    const SUPER_ADMIN = 'super-admin';
    const ADMIN = 'admin';
    const STAFF = 'staff';
    const EDITOR = 'editor';
    const USER = 'user';
}