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
        Permissions::AUTHORS_CREATE,
        Permissions::AUTHORS_GET,
        Permissions::AUTHORS_LIST,
        Permissions::AUTHORS_UPDATE,
        Permissions::AUTHORS_DELETE,
        Permissions::BOOKS_CREATE,
        Permissions::BOOKS_GET,
        Permissions::BOOKS_LIST,
        Permissions::BOOKS_UPDATE,
        Permissions::BOOKS_DELETE,
        Permissions::CATEGORIES_CREATE,
        Permissions::CATEGORIES_GET,
        Permissions::CATEGORIES_LIST,
        Permissions::CATEGORIES_UPDATE,
        Permissions::CATEGORIES_DELETE,
        Permissions::COUNCILS_CREATE,
        Permissions::COUNCILS_GET,
        Permissions::COUNCILS_LIST,
        Permissions::COUNCILS_UPDATE,
        Permissions::COUNCILS_DELETE,
        Permissions::LIBRARIES_CREATE,
        Permissions::LIBRARIES_GET,
        Permissions::LIBRARIES_LIST,
        Permissions::LIBRARIES_UPDATE,
        Permissions::LIBRARIES_DELETE,
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
        Permissions::AUTHORS_GET,
        Permissions::AUTHORS_LIST,
        Permissions::BOOKS_GET,
        Permissions::BOOKS_LIST,
        Permissions::CATEGORIES_GET,
        Permissions::CATEGORIES_LIST,
        Permissions::COUNCILS_GET,
        Permissions::COUNCILS_LIST,
        Permissions::LIBRARIES_GET,
        Permissions::LIBRARIES_LIST,
        Permissions::USERS_GET,
        Permissions::USERS_LIST,
    ],

    /*
    |--------------------------------------------------------------------------
    | Editor permissions
    |--------------------------------------------------------------------------
    */

    Roles::EDITOR => [
        Permissions::AUTHORS_CREATE,
        Permissions::AUTHORS_GET,
        Permissions::AUTHORS_LIST,
        Permissions::AUTHORS_UPDATE,
        Permissions::BOOKS_CREATE,
        Permissions::BOOKS_GET,
        Permissions::BOOKS_LIST,
        Permissions::BOOKS_UPDATE,
        Permissions::CATEGORIES_GET,
        Permissions::CATEGORIES_LIST,
        Permissions::COUNCILS_CREATE,
        Permissions::COUNCILS_GET,
        Permissions::COUNCILS_LIST,
        Permissions::COUNCILS_UPDATE,
        Permissions::LIBRARIES_CREATE,
        Permissions::LIBRARIES_GET,
        Permissions::LIBRARIES_LIST,
        Permissions::LIBRARIES_UPDATE,
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
        Permissions::AUTHORS_GET,
        Permissions::AUTHORS_LIST,
        Permissions::BOOKS_GET,
        Permissions::BOOKS_LIST,
        Permissions::CATEGORIES_GET,
        Permissions::CATEGORIES_LIST,
        Permissions::COUNCILS_GET,
        Permissions::COUNCILS_LIST,
        Permissions::LIBRARIES_GET,
        Permissions::LIBRARIES_LIST,
        Permissions::USERS_GET,
    ],

    /*
    |--------------------------------------------------------------------------
    | User permissions
    |--------------------------------------------------------------------------
    */

    Roles::SUPER_ADMIN => Permissions::getConstants(),
];