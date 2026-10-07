<?php

namespace AlexRoden\LibraryApiPhp\Models;

use AlexRoden\LibraryApiPhp\Exceptions\UndefinedClassException;

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
        'published_at',
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
        $author = $this->getModelId($author);

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

    public function assignCategory(Category|int $category): void
    {
        $category = $this->getModelId($category);

        if (
            !$this->DB(
                'book_categories',
                null,
                ['book_id', 'category_id'],
            )->where(
                'book_id',
                '=',
                $this->id,
            )->where(
                'category_id',
                '=',
                $category
            )->first()
        ) {
            $this->DB(
                'book_categories',
                null,
                ['book_id', 'category_id'],
            )->insert([
                'book_id' => $this->id,
                'category_id' => $category,
            ]);
        }
    }

    public function categories(): array
    {
        return $this->DB(
            'book_categories',
            Category::class,
        )->where(
            'book_id',
            '=',
            $this->id,
        )->join(
            'categories',
            'category_id',
            'id',
            ['id', 'name'],
        )->excludeLocalAttributes()->get();
    }

    /**
     * The libraries stocking this book, each carrying its `quantity` from `stocks`.
     *
     * @return array<Library>
     *
     * @throws UndefinedClassException
     */
    public function libraries(): array
    {
        return $this->DB(
            'stocks',
            Library::class,
        )->where(
            'book_id',
            '=',
            $this->id,
        )->join(
            'libraries',
            'library_id',
            'id',
            [
                'id',
                'name',
                'created_at',
                'updated_at',
                'stocks.quantity',
            ],
        )->excludeLocalAttributes()->get();
    }

    public function tags(): array
    {
        return explode(',', $this->tags);
    }
}
