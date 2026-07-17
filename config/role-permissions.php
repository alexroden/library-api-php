<?php

use AlexRoden\LibraryApiPhp\Enums\Permissions;
use AlexRoden\LibraryApiPhp\Enums\Roles;

return [


    /*
    |--------------------------------------------------------------------------
    | Admin permissions
    |--------------------------------------------------------------------------
    */

    Roles::ADMIN => [
        Permissions::USERS_CREATE,
        Permissions::USERS_GET,
        Permissions::USERS_LIST,
        Permissions::USERS_UPDATE,
        Permissions::USERS_DELETE,
    ],


    /*
    |--------------------------------------------------------------------------
    | Staff permissions
    |--------------------------------------------------------------------------
    */

    Roles::STAFF => [
        Permissions::USERS_GET,
        Permissions::USERS_LIST,
    ],

    /*
    |--------------------------------------------------------------------------
    | Editor permissions
    |--------------------------------------------------------------------------
    */

    Roles::EDITOR => [
        Permissions::USERS_CREATE,
        Permissions::USERS_GET,
        Permissions::USERS_LIST,
        Permissions::USERS_UPDATE,
    ],

    /*
    |--------------------------------------------------------------------------
    | User permissions
    |--------------------------------------------------------------------------
    */

    Roles::USER => [
        Permissions::USERS_GET,
    ],

    /*
    |--------------------------------------------------------------------------
    | User permissions
    |--------------------------------------------------------------------------
    */

    Roles::SUPER_ADMIN => Permissions::getConstants(),
];