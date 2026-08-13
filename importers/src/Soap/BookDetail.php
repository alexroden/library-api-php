<?php

namespace AlexRoden\Importers\Soap;

/**
 * The full book record returned by the getBook method.
 */
readonly class BookDetail
{
    /**
     * @param string[] $tags
     */
    public function __construct(
        public int $id,
        public string $title,
        public string $description,
        public array $tags,
        public string $category,
        public string $author,
        public ?string $publishedAt = null,
    ) {}

    public static function fromResponse(object $book): self
    {
        /*
         * tags is a repeated element, so it arrives as an object wrapping a
         * list of tags, a single tag on its own, or nothing at all.
         */
        $tags = $book->tags->tag ?? [];
        if (!is_array($tags)) {
            $tags = [$tags];
        }

        /*
         * The api calls it shortDescription, the books table calls it
         * description.
         */
        return new self(
            (int) $book->id,
            (string) $book->title,
            (string) ($book->shortDescription ?? ''),
            array_map(strval(...), $tags),
            (string) ($book->category ?? ''),
            (string) $book->author,
            /*
             * publishedAt was added to the feed after the first books were
             * imported, so a record without one is still accepted.
             */
            isset($book->publishedAt) ? (string) $book->publishedAt : null,
        );
    }
}
