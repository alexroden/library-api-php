<?php

namespace AlexRoden\LibraryApiPhp\Tests\Features\Books;

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

        $response = $this->handle(
            Request::create(
                method: 'POST',
                uri: '/api/books',
                body: [
                    'title' => $title,
                    'description' => $description,
                    'tags' => $tags,
                    'authors' => [$this->author->id]
                ]
            )
        );

        $this->assertEquals(201, $response->status());

        $book = Book::where('title', '=', $title)->first();
        $this->assertNotNull($book);
        $this->assertEquals($title, $book->title);
        $this->assertEquals($description, $book->description);
        $this->assertEquals($tags, $book->tags());
    }
}