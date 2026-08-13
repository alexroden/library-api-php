<?php

namespace AlexRoden\LibraryApiPhp\Tests\Features\Books;

use AlexRoden\LibraryApiPhp\Http\Foundation\Request;
use AlexRoden\LibraryApiPhp\Models\Book;
use AlexRoden\LibraryApiPhp\Tests\Factories\BookFactory;
use AlexRoden\LibraryApiPhp\Tests\Features\AbstractFeaturesTestCase;

class GetTest extends AbstractFeaturesTestCase
{
    private Book $book;

    protected function setUp(): void
    {
        parent::setUp();

        $this->book = BookFactory::create();
    }

    public function testGetCategory(): void
    {
        $this->asAuthorizedUser();

        $response = $this->handle(
            Request::create(
                method: 'GET',
                uri: "/api/books/{$this->book->id}",
            )
        );

        $this->assertEquals(200, $response->status());
        $this->assertSame(
            $this->book->toArray(),
            $response->json()['data']->toArray()
        );
    }
}
