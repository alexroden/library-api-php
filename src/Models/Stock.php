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
     * A library holds one stock record per book, so this is the pair the unique
     * constraint is on.
     *
     * @throws UndefinedClassException
     */
    public static function findByLibraryAndBook(int $libraryId, int $bookId): ?Stock
    {
        return static::where(
            'library_id',
            '=',
            $libraryId,
        )->where(
            'book_id',
            '=',
            $bookId,
        )->first();
    }

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
