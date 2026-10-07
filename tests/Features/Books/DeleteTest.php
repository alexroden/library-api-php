<?php

namespace AlexRoden\LibraryApiPhp\Tests\Features\Books;

use AlexRoden\LibraryApiPhp\Http\Foundation\Request;
use AlexRoden\LibraryApiPhp\Models\Book;
use AlexRoden\LibraryApiPhp\Tests\Factories\BookFactory;
use AlexRoden\LibraryApiPhp\Tests\Features\AbstractFeaturesTestCase;

class DeleteTest extends AbstractFeaturesTestCase
{
    private Book $book;

    protected function setUp(): void
    {
        parent::setUp();

        $this->book = BookFactory::create();
    }

    public function testDeleteBook(): void
    {
        $this->asAuthorizedUser();

        $response = $this->handle(
            Request::create(
                method: 'DELETE',
                uri: "/api/books/{$this->book->id}",
            )
        );

        $this->assertEquals(204, $response->status());

        $book = Book::where('id', '=', $this->book->id)->first();
        $this->assertNull($book);
    }
}
