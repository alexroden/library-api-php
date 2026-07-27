<?php

namespace AlexRoden\LibraryApiPhp\Models;

/**
 * @extends AbstractModel<Library>
 */
class Library extends AbstractModel
{
    protected string $table = 'libraries';

    protected array $fillable = [
        'name',
    ];
}