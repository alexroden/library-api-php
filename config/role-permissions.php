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
        Permissions::COUNCILS_CREATE,
        Permissions::COUNCILS_GET,
        Permissions::COUNCILS_LIST,
        Permissions::COUNCILS_UPDATE,
        Permissions::COUNCILS_DELETE,
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
        Permissions::COUNCILS_GET,
        Permissions::COUNCILS_LIST,
        Permissions::USERS_GET,
        Permissions::USERS_LIST,
    ],

    /*
    |--------------------------------------------------------------------------
    | Editor permissions
    |--------------------------------------------------------------------------
    */

    Roles::EDITOR => [
        Permissions::COUNCILS_CREATE,
        Permissions::COUNCILS_GET,
        Permissions::COUNCILS_LIST,
        Permissions::COUNCILS_UPDATE,
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
        Permissions::COUNCILS_GET,
        Permissions::COUNCILS_LIST,
        Permissions::USERS_GET,
    ],

    /*
    |--------------------------------------------------------------------------
    | User permissions
    |--------------------------------------------------------------------------
    */

    Roles::SUPER_ADMIN => Permissions::getConstants(),
];