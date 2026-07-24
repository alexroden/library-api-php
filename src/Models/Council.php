<?php

namespace AlexRoden\LibraryApiPhp\Models;

/**
 * @extends AbstractModel<Council>
 */
class Council extends AbstractModel
{
    protected string $table = 'councils';

    protected array $fillable = [
        'name',
    ];
}