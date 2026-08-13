<?php

namespace AlexRoden\LibraryApiPhp\Tests\Unit\Models;

use AlexRoden\LibraryApiPhp\Models\Author;
use AlexRoden\LibraryApiPhp\Models\Book;
use AlexRoden\LibraryApiPhp\Models\Category;
use AlexRoden\LibraryApiPhp\Tests\AbstractTestCase;
use AlexRoden\LibraryApiPhp\Tests\Factories\AuthorFactory;
use AlexRoden\LibraryApiPhp\Tests\Factories\BookFactory;
use AlexRoden\LibraryApiPhp\Tests\Factories\CategoryFactory;

class BookTest extends AbstractTestCase
{
    private Book $book;

    protected function setUp(): void
    {
        parent::setUp();

        $this->book = BookFactory::create();
    }

    public function testCreate(): void
    {
        $book = Book::create([
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'tags' => implode(',', $this->faker->words(2)),
        ]);

        $this->assertNotNull($book->id);
    }

    public function testDelete(): void
    {
        $this->book->delete();

        $book = Book::where('id', '=', $this->book->id)->first();

        $this->assertNull($book);
    }

    public function testGet(): void
    {
        $book = Book::where('id', '=', $this->book->id)->get();

        $this->assertCount(1, $book);
    }

    public function testFirst(): void
    {
        $book = Book::where('id', '=', $this->book->id)->first();

        $this->assertEquals($this->book->id, $book->id);
    }

    public function testUpdate(): void
    {
        $title = $this->faker->sentence();
        $description = $this->faker->paragraph();
        $tags = implode(',', $this->faker->words(2));

        $this->book->update([
            'title' => $title,
            'description' => $description,
            'tags' => $tags,
        ]);

        $book = $this->book->refresh();

        $this->assertSame([
            'title' => $title,
            'description' => $description,
            'tags' => $tags,
        ], [
            'title' => $book->title,
            'description' => $book->description,
            'tags' => $book->tags,
        ]);
    }

    public function testAuthors(): void
    {
        /** @var Author $author */
        $author = AuthorFactory::create();
        $this->book->assignAuthor($author);

        $authors = $this->book->authors();
        $this->assertCount(1, $authors);
        $this->assertJson($author->toJson(), $authors[0]->toJson());
    }

    public function testCategories(): void
    {
        /** @var Category $category */
        $category = CategoryFactory::create();
        $this->book->assignCategory($category);

        $categories = $this->book->categories();
        $this->assertCount(1, $categories);
        $this->assertJson($category->toJson(), $categories[0]->toJson());
    }
}