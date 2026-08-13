<?php

namespace AlexRoden\LibraryApiPhp\Tests\Unit\Models;

use AlexRoden\LibraryApiPhp\Models\Author;
use AlexRoden\LibraryApiPhp\Models\Book;
use AlexRoden\LibraryApiPhp\Tests\AbstractTestCase;
use AlexRoden\LibraryApiPhp\Tests\Factories\AuthorFactory;
use AlexRoden\LibraryApiPhp\Tests\Factories\BookFactory;

class AuthorTest extends AbstractTestCase
{
    private Author $author;

    protected function setUp(): void
    {
        parent::setUp();

        $this->author = AuthorFactory::create();
    }

    public function testCreate(): void
    {
        $author = Author::create([
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
        ]);

        $this->assertNotNull($author);
    }

    public function testDelete(): void
    {
        $this->author->delete();

        $author = Author::where('first_name', '=', $this->author->first_name)->where('last_name', '=', $this->author->last_name)->first();

        $this->assertNull($author);
    }

    public function testGet(): void
    {
        $author = Author::where('id', '=', $this->author->id)->first();

        $this->assertEquals($this->author->id, $author->id);
    }

    public function testUpdate(): void
    {
        $firstName = $this->faker->firstName;
        $lastName = $this->faker->lastName;

        $this->author->update([
            'first_name' => $firstName,
            'last_name' => $lastName,
        ]);

        $author = $this->author->refresh();

        $this->assertSame([
            'first_name' => $firstName,
            'last_name' => $lastName,
        ], [
            'first_name' => $author->first_name,
            'last_name' => $author->last_name,
        ]);
    }

    public function testBooks(): void
    {
        /** @var Book $book */
        $book = BookFactory::create();
        $book->assignAuthor($this->author);

        $books = $this->author->books();
        $this->assertCount(1, $books);
        $this->assertJson($book->toJson(), $books[0]->toJson());
    }
}
