<?php

namespace AlexRoden\LibraryApiPhp\Models;

class Role extends AbstractModel
{
    protected string $table = 'roles';

    protected array $fillable = [
        'name',
    ];
}