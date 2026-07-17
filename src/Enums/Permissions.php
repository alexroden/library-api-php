<?php

namespace AlexRoden\LibraryApiPhp\Enums;

use AlexRoden\LibraryApiPhp\Enums\Concerns\ConstantsTrait;

final class Permissions
{
    use ConstantsTrait;

    const USERS_CREATE = 'users.create';
    const USERS_GET = 'users.get';
    const USERS_LIST = 'users.list';
    const USERS_UPDATE = 'users.update';
    const USERS_DELETE = 'users.delete';
}