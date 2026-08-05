<?php

namespace AlexRoden\LibraryApiPhp\Models;

/**
 * @extends AbstractModel<Category>
 */
class Category extends AbstractModel
{
    protected string $table = 'categories';

    protected array $fillable = [
        'name',
    ];
}