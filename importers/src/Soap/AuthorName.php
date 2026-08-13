<?php

namespace AlexRoden\Importers\Soap;

use InvalidArgumentException;

/**
 * The api exposes an author as a single string, so it has to be split before
 * it can be written to the authors table.
 */
readonly class AuthorName
{
    public function __construct(
        public string $firstName,
        public string $lastName,
    ) {}

    /**
     * The first word is taken as the first name and everything after it as the
     * last name, so "Jean de la Fontaine" keeps "de la Fontaine" together. A
     * name that is a single word leaves the last name empty.
     *
     * @throws InvalidArgumentException
     */
    public static function fromString(string $author): self
    {
        $author = trim(preg_replace('/\s+/', ' ', $author));

        if ($author === '') {
            throw new InvalidArgumentException('Author name is empty.');
        }

        $parts = explode(' ', $author, 2);

        return new self(
            $parts[0],
            $parts[1] ?? '',
        );
    }
}
