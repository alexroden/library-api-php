<?php

namespace AlexRoden\LibraryApiPhp\Tests\Features\Books;

use AlexRoden\LibraryApiPhp\Http\Exceptions\ValidationException;
use AlexRoden\LibraryApiPhp\Http\Foundation\Request;
use AlexRoden\LibraryApiPhp\Models\Author;
use AlexRoden\LibraryApiPhp\Models\Book;
use AlexRoden\LibraryApiPhp\Tests\Factories\AuthorFactory;
use AlexRoden\LibraryApiPhp\Tests\Features\AbstractFeaturesTestCase;

class CreateTest extends AbstractFeaturesTestCase
{
    private Author $author;
    protected function setUp(): void
    {
        parent::setUp();

        $this->author = AuthorFactory::create();
    }

    public function testCreateBook(): void
    {
        $this->asAuthorizedUser();

        $title = $this->faker->sentence();
        $description = $this->faker->paragraph();
        $tags = [$this->faker->word(), $this->faker->word()];
        $publishedAt = $this->faker->date();

        $response = $this->handle(
            Request::create(
                method: 'POST',
                uri: '/api/books',
                body: [
                    'title' => $title,
                    'description' => $description,
                    'tags' => $tags,
                    'authors' => [$this->author->id],
                    'published_at' => $publishedAt,
                ]
            )
        );

        $this->assertEquals(201, $response->status());

        $book = Book::where('title', '=', $title)->first();
        $this->assertNotNull($book);
        $this->assertEquals($title, $book->title);
        $this->assertEquals($description, $book->description);
        $this->assertEquals($tags, $book->tags());
        $this->assertEquals($publishedAt, $book->published_at);
    }

    public function testCreateBookWithoutPublishedAt(): void
    {
        $this->asAuthorizedUser();

        $title = $this->faker->sentence();

        $response = $this->handle(
            Request::create(
                method: 'POST',
                uri: '/api/books',
                body: [
                    'title' => $title,
                    'description' => $this->faker->paragraph(),
                ]
            )
        );

        $this->assertEquals(201, $response->status());

        $book = Book::where('title', '=', $title)->first();
        $this->assertNotNull($book);
        $this->assertNull($book->published_at);
    }

    public function testCreateBookRejectsAnInvalidPublishedAt(): void
    {
        $this->asAuthorizedUser();

        $title = $this->faker->sentence();

        /*
         * index.php is the only error boundary, so a validation failure leaves
         * the request as an exception rather than a response.
         */
        try {
            $this->handle(
                Request::create(
                    method: 'POST',
                    uri: '/api/books',
                    body: [
                        'title' => $title,
                        'description' => $this->faker->paragraph(),
                        'published_at' => '28/01/1813',
                    ]
                )
            );

            $this->fail('Expected the request to fail validation.');
        } catch (ValidationException $e) {
            $this->assertEquals(422, $e->statusCode());
            $this->assertArrayHasKey('published_at', $e->errors());
        }

        $this->assertNull(Book::where('title', '=', $title)->first());
    }
}
