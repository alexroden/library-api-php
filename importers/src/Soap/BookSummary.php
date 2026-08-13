<?php

namespace AlexRoden\Importers\Soap;

/**
 * The trimmed down book returned by the getBooks list method.
 */
readonly class BookSummary
{
    public function __construct(
        public int $id,
        public string $title,
        public string $author,
    ) {}

    public static function fromResponse(object $book): self
    {
        return new self(
            (int) $book->id,
            (string) $book->title,
            (string) $book->author,
        );
    }
}
