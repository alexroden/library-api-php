<?php

namespace AlexRoden\LibraryApiPhp\Tests\Features\Books;

use AlexRoden\LibraryApiPhp\Http\Foundation\Request;
use AlexRoden\LibraryApiPhp\Models\Book;
use AlexRoden\LibraryApiPhp\Models\Category;
use AlexRoden\LibraryApiPhp\Tests\Factories\BookFactory;
use AlexRoden\LibraryApiPhp\Tests\Features\AbstractFeaturesTestCase;

class UpdateTest extends AbstractFeaturesTestCase
{
    private Book $book;

    protected function setUp(): void
    {
        parent::setUp();

        $this->book = BookFactory::create();
    }

    public function testUpdateBook(): void
    {
        $this->asAuthorizedUser();

        $title = $this->faker->sentence();
        $description = $this->faker->paragraph();
        $tags = [$this->faker->word(), $this->faker->word()];

        $response = $this->handle(
            Request::create(
                method: 'PUT',
                uri: "/api/books/{$this->book->id}",
                body: [
                    'title' => $title,
                    'description' => $description,
                    'tags' => $tags,
                ]
            )
        );

        $this->assertEquals(200, $response->status());

        $book = Book::where('title', '=', $title)->first();
        $this->assertNotNull($book);
        $this->assertSame([
            'title' => $title,
            'description' => $description,
            'tags' => $tags,
        ], [
            'title' => $book->title,
            'description' => $book->description,
            'tags' => $book->tags(),
        ]);
    }
}