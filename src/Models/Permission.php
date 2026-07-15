<?php

namespace AlexRoden\LibraryApiPhp\Models;

class Permission extends AbstractModel
{
    protected string $table = 'permissions';

    protected array $fillable = [
        'name',
    ];
}