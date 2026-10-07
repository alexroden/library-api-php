<?php

namespace AlexRoden\LibraryApiPhp\Models;

/**
 * @extends AbstractModel<Permission>
 */
class Permission extends AbstractModel
{
    protected string $table = 'permissions';

    protected array $fillable = [
        'name',
    ];
}
