<?php

namespace AlexRoden\LibraryApiPhp\Enums;

use AlexRoden\LibraryApiPhp\Enums\Concerns\ConstantsTrait;

final class Permissions
{
    use ConstantsTrait;

    const string USERS_CREATE = 'users.create';
    const string USERS_GET = 'users.get';
    const string USERS_LIST = 'users.list';
    const string USERS_UPDATE = 'users.update';
    const string USERS_DELETE = 'users.delete';
}