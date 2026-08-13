<?php

namespace AlexRoden\LibraryApiPhp\Enums;

use AlexRoden\LibraryApiPhp\Enums\Concerns\ConstantsTrait;

final class Roles
{
    use ConstantsTrait;

    const string SUPER_ADMIN = 'super-admin';
    const string ADMIN = 'admin';
    const string STAFF = 'staff';
    const string EDITOR = 'editor';
    const string USER = 'user';
}
