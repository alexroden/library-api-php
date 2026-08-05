<?php

namespace AlexRoden\LibraryApiPhp\Models;

/**
 * @extends AbstractModel<Book>
 */
class Book extends AbstractModel
{
    protected string $table = 'books';

    protected array $fillable = [
        'title',
        'description',
        'tags',
    ];

    public function authors(): array
    {
        return $this->DB(
            'book_authors',
            Author::class,
        )->where(
            'book_id',
            '=',
            $this->id,
        )->join(
            'authors',
            'author_id',
            'id',
            ['id', 'first_name', 'last_name'],
        )->excludeLocalAttributes()->get();
    }

    public function assignAuthor(Author|int $author): void
    {
        $author = $this->getAuthorId($author);

        if (
            !$this->DB(
                'book_authors',
                null,
                ['book_id', 'author_id'],
            )->where(
                'book_id',
                '=',
                $this->id,
            )->where(
                'author_id',
                '=',
                $author
            )->first()
        ) {
            $this->DB(
                'book_authors',
                null,
                ['book_id', 'author_id'],
            )->insert([
                'book_id' => $this->id,
                'author_id' => $author,
            ]);
        }
    }

    public function tags(): array
    {
        return explode(',', $this->tags);
    }

    private function getAuthorId(Author|int $author): int
    {
        if (!is_int($author)) {
            return $author->id;
        }

        return $author;
    }
}