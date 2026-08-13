<?php

namespace AlexRoden\LibraryApiPhp\Tests\Unit\Models;

use AlexRoden\LibraryApiPhp\Models\Book;
use AlexRoden\LibraryApiPhp\Models\Library;
use AlexRoden\LibraryApiPhp\Models\Stock;
use AlexRoden\LibraryApiPhp\Tests\AbstractTestCase;
use AlexRoden\LibraryApiPhp\Tests\Factories\BookFactory;
use AlexRoden\LibraryApiPhp\Tests\Factories\LibraryFactory;

class LibraryTest extends AbstractTestCase
{
    private Library $library;

    protected function setUp(): void
    {
        parent::setUp();

        $this->library = LibraryFactory::create();
    }

    public function testCreate(): void
    {
        $library = Library::create([
            'name' => $this->faker->city(),
        ]);

        $this->assertNotNull($library->id);
    }

    public function testDelete(): void
    {
        $this->library->delete();

        $library = Library::where('id', '=', $this->library->id)->first();

        $this->assertNull($library);
    }

    public function testGet(): void
    {
        $libraries = Library::where('id', '=', $this->library->id)->get();

        $this->assertCount(1, $libraries);
    }

    public function testFirst(): void
    {
        $library = Library::where('id', '=', $this->library->id)->first();

        $this->assertEquals($this->library->id, $library->id);
    }

    public function testUpdate(): void
    {
        $name = $this->faker->city();

        $this->library->update([
            'name' => $name,
        ]);

        $library = $this->library->refresh();

        $this->assertSame([
            'name' => $name,
        ], [
            'name' => $library->name,
        ]);
    }

    public function testBooks(): void
    {
        /** @var Book $book */
        $book = BookFactory::create();
        Stock::create([
            'library_id' => $this->library->id,
            'book_id' => $book->id,
            'quantity' => 3,
        ]);

        $books = $this->library->books();

        $this->assertCount(1, $books);
        $this->assertEquals($book->id, $books[0]->id);
        $this->assertEquals($book->title, $books[0]->title);
        $this->assertEquals(3, $books[0]->quantity);
    }

    public function testBooksOnlyIncludesStockedBooks(): void
    {
        /** @var Book $stocked */
        $stocked = BookFactory::create();
        BookFactory::create();

        Stock::create([
            'library_id' => $this->library->id,
            'book_id' => $stocked->id,
            'quantity' => 1,
        ]);

        $books = $this->library->books();

        $this->assertCount(1, $books);
        $this->assertEquals($stocked->id, $books[0]->id);
    }

    public function testBooksIsEmptyWithoutStock(): void
    {
        BookFactory::create();

        $this->assertSame([], $this->library->books());
    }
}
