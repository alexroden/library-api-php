<?php

namespace AlexRoden\LibraryApiPhp\Models;

use AlexRoden\LibraryApiPhp\Exceptions\UndefinedClassException;

/**
 * @extends AbstractModel<Library>
 */
class Library extends AbstractModel
{
    protected string $table = 'libraries';

    protected array $fillable = [
        'name',
    ];

    /**
     * The books this library stocks, each carrying its `quantity` from `stocks`.
     *
     * @return array<Book>
     *
     * @throws UndefinedClassException
     */
    public function books(): array
    {
        return $this->DB(
            'stocks',
            Book::class,
        )->where(
            'library_id',
            '=',
            $this->id,
        )->join(
            'books',
            'book_id',
            'id',
            [
                'id',
                'title',
                'description',
                'tags',
                'published_at',
                'created_at',
                'updated_at',
                'stocks.quantity',
            ],
        )->excludeLocalAttributes()->get();
    }
}
