<?php

namespace AlexRoden\LibraryApiPhp\Models;

class User extends AbstractModel
{
    protected string $table = 'users';

    protected array $fillable = [
        'email',
        'password',
        'first_name',
        'last_name',
    ];
}