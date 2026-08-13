<?php

namespace AlexRoden\LibraryApiPhp\Soap;

use stdClass;

class BookService
{
    public function getBooks(): array
    {
        $books = $this->loadBooks();

        return array_map(
            static fn (array $book): array => [
                'id' => $book['id'],
                'title' => $book['title'],
                'author' => $book['author'],
            ],
            $books
        );
    }

    public function getBook(\stdClass $request): array
    {
        $id = (int) $request->id;

        foreach ($this->loadBooks() as $book) {
            if ($book['id'] === $id) {
                return [
                    'book' => $book,
                ];
            }
        }

        throw new \RuntimeException("Book with ID {$id} not found");
    }

    private function loadBooks(): array
    {
        $file = __DIR__ . '/books.json';

        if (!file_exists($file)) {
            throw new \RuntimeException('Books data file not found');
        }

        $json = file_get_contents($file);

        $books = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException(
                'Invalid books JSON: ' . json_last_error_msg()
            );
        }

        return $books;
    }
}