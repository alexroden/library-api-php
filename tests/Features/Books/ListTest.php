<?php

namespace AlexRoden\LibraryApiPhp\Tests\Features\Books;

use AlexRoden\LibraryApiPhp\Http\Foundation\Request;
use AlexRoden\LibraryApiPhp\Models\Book;
use AlexRoden\LibraryApiPhp\Tests\Factories\BookFactory;
use AlexRoden\LibraryApiPhp\Tests\Features\AbstractFeaturesTestCase;

class ListTest extends AbstractFeaturesTestCase
{
    private Book $book;

    protected function setUp(): void
    {
        parent::setUp();

        $this->book = BookFactory::create();
    }

    public function testListBooks(): void
    {
        $this->asAuthorizedUser();

        $response = $this->handle(
            Request::create(
                method: 'GET',
                uri: '/api/books',
            )
        );

        $this->assertEquals(200, $response->status());
        $this->assertSame(
            $this->book->toArray(),
            $response->json()['data'][0]->toArray()
        );
    }
}
