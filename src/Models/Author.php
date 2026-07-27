<?php

namespace AlexRoden\LibraryApiPhp\Models;


/**
 * @extends AbstractModel<Author>
 */
class Author extends AbstractModel
{
    protected string $table = 'authors';

    protected array $fillable = [
        'first_name',
        'last_name',
    ];
}