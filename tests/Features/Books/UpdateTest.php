<?php

namespace AlexRoden\LibraryApiPhp\Tests\Features\Books;

use AlexRoden\LibraryApiPhp\Http\Exceptions\ValidationException;
use AlexRoden\LibraryApiPhp\Http\Foundation\Request;
use AlexRoden\LibraryApiPhp\Models\Book;
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
        $publishedAt = $this->faker->date();

        $response = $this->handle(
            Request::create(
                method: 'PUT',
                uri: "/api/books/{$this->book->id}",
                body: [
                    'title' => $title,
                    'description' => $description,
                    'tags' => $tags,
                    'published_at' => $publishedAt,
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
            'published_at' => $publishedAt,
        ], [
            'title' => $book->title,
            'description' => $book->description,
            'tags' => $book->tags(),
            'published_at' => $book->published_at,
        ]);
    }

    public function testUpdateBookRejectsAnInvalidPublishedAt(): void
    {
        $this->asAuthorizedUser();

        $this->expectException(ValidationException::class);

        $this->handle(
            Request::create(
                method: 'PUT',
                uri: "/api/books/{$this->book->id}",
                body: [
                    'title' => $this->faker->sentence(),
                    'description' => $this->faker->paragraph(),
                    'published_at' => 'not a date',
                ]
            )
        );
    }
}