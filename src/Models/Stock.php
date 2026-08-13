<?php

namespace AlexRoden\LibraryApiPhp\Models;

use AlexRoden\LibraryApiPhp\Exceptions\UndefinedClassException;

/**
 * @extends AbstractModel<Stock>
 */
class Stock extends AbstractModel
{
    protected string $table = 'stocks';

    protected array $fillable = [
        'library_id',
        'book_id',
        'quantity',
    ];

    /**
     * @throws UndefinedClassException
     */
    public function library(): ?Library
    {
        return Library::find($this->library_id);
    }

    /**
     * @throws UndefinedClassException
     */
    public function book(): ?Book
    {
        return Book::find($this->book_id);
    }
}
