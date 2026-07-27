<?php

namespace AlexRoden\LibraryApiPhp\Enums;

use AlexRoden\LibraryApiPhp\Enums\Concerns\ConstantsTrait;

final class Permissions
{
    use ConstantsTrait;

    const string AUTHORS_CREATE = 'authors.create';
    const string AUTHORS_GET = 'authors.get';
    const string AUTHORS_LIST = 'authors.list';
    const string AUTHORS_UPDATE = 'authors.update';
    const string AUTHORS_DELETE = 'authors.delete';
    const string COUNCILS_CREATE = 'councils.create';
    const string COUNCILS_GET = 'councils.get';
    const string COUNCILS_LIST = 'councils.list';
    const string COUNCILS_UPDATE = 'councils.update';
    const string COUNCILS_DELETE = 'councils.delete';
    const string LIBRARIES_CREATE = 'libraries.create';
    const string LIBRARIES_GET = 'libraries.get';
    const string LIBRARIES_LIST = 'libraries.list';
    const string LIBRARIES_UPDATE = 'libraries.update';
    const string LIBRARIES_DELETE = 'libraries.delete';
    const string USERS_CREATE = 'users.create';
    const string USERS_GET = 'users.get';
    const string USERS_LIST = 'users.list';
    const string USERS_UPDATE = 'users.update';
    const string USERS_DELETE = 'users.delete';
}