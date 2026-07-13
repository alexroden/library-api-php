<?php

namespace App\Enums;

use App\Enums\Concerns\ConstantsTrait;

final class Roles
{
    use ConstantsTrait;

    const SUPER_ADMIN = 'admin';
    const ADMIN = 'admin';
    const STAFF = 'staff';
    const EDITOR = 'editor';
    const USER = 'user';
}