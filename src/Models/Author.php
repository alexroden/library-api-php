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

    public function books(): array
    {
        return $this->DB(
            'book_authors',
            Book::class,
        )->where(
            'author_id',
            '=',
            $this->id,
        )->join(
            'books',
            'book_id',
            'id',
            ['id', 'title', 'description', 'tags'],
        )->excludeLocalAttributes()->get();
    }
}
