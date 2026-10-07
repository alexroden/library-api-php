<?php

namespace AlexRoden\LibraryApiPhp\Models;

use AlexRoden\LibraryApiPhp\Database\DB;
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
     * @return array<Stock>
     *
     * @throws UndefinedClassException
     */
    public static function getForLibrary(
        Library $library,
        ?int $bookId = null,
        ?int $limit = null,
        ?int $offset = null,
    ): array {
        return static::forLibrary(new static()->DB(), $library, $bookId)->get($limit, $offset);
    }

    /**
     * @throws UndefinedClassException
     */
    public static function countForLibrary(Library $library, ?int $bookId = null): int
    {
        return static::count(static::forLibrary(static::countQuery(), $library, $bookId));
    }

    /**
     * @return array<Stock>
     *
     * @throws UndefinedClassException
     */
    public static function getForBook(
        Book $book,
        ?int $libraryId = null,
        ?int $limit = null,
        ?int $offset = null,
    ): array {
        return static::forBook(new static()->DB(), $book, $libraryId)->get($limit, $offset);
    }

    /**
     * @throws UndefinedClassException
     */
    public static function countForBook(Book $book, ?int $libraryId = null): int
    {
        return static::count(static::forBook(static::countQuery(), $book, $libraryId));
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

    /**
     * @param DB<Stock> $query
     *
     * @return DB<Stock>
     */
    private static function forLibrary(DB $query, Library $library, ?int $bookId): DB
    {
        $query->where('library_id', '=', $library->id);

        if ($bookId !== null) {
            $query->where('book_id', '=', $bookId);
        }

        return $query;
    }

    /**
     * @param DB<Stock> $query
     *
     * @return DB<Stock>
     */
    private static function forBook(DB $query, Book $book, ?int $libraryId): DB
    {
        $query->where('book_id', '=', $book->id);

        if ($libraryId !== null) {
            $query->where('library_id', '=', $libraryId);
        }

        return $query;
    }

    /**
     * @return DB<Stock>
     */
    private static function countQuery(): DB
    {
        return new DB(table: new static()->table, attributes: ['COUNT(*) AS total']);
    }

    /**
     * @param DB<Stock> $query
     *
     * @throws UndefinedClassException
     */
    private static function count(DB $query): int
    {
        return (int) $query->get(excludeModelMapping: true)[0]['total'];
    }
}
