<?php

namespace AlexRoden\LibraryApiPhp\Tests\Unit\Models;

use AlexRoden\LibraryApiPhp\Models\Book;
use AlexRoden\LibraryApiPhp\Models\Library;
use AlexRoden\LibraryApiPhp\Models\Stock;
use AlexRoden\LibraryApiPhp\Tests\AbstractTestCase;
use AlexRoden\LibraryApiPhp\Tests\Factories\BookFactory;
use AlexRoden\LibraryApiPhp\Tests\Factories\LibraryFactory;
use AlexRoden\LibraryApiPhp\Tests\Factories\StockFactory;

class StockTest extends AbstractTestCase
{
    private Stock $stock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->stock = StockFactory::create();
    }

    public function testCreate(): void
    {
        /** @var Library $library */
        $library = LibraryFactory::create();
        /** @var Book $book */
        $book = BookFactory::create();

        $stock = Stock::create([
            'library_id' => $library->id,
            'book_id' => $book->id,
            'quantity' => 5,
        ]);

        $this->assertNotNull($stock->id);
        $this->assertEquals(5, $stock->quantity);
    }

    public function testDelete(): void
    {
        $this->stock->delete();

        $stock = Stock::where('id', '=', $this->stock->id)->first();

        $this->assertNull($stock);
    }

    public function testGet(): void
    {
        $stock = Stock::where('id', '=', $this->stock->id)->get();

        $this->assertCount(1, $stock);
    }

    public function testFirst(): void
    {
        $stock = Stock::where('id', '=', $this->stock->id)->first();

        $this->assertEquals($this->stock->id, $stock->id);
    }

    public function testUpdate(): void
    {
        $this->stock->update([
            'quantity' => 42,
        ]);

        $stock = $this->stock->refresh();

        $this->assertEquals(42, $stock->quantity);
    }

    public function testLibrary(): void
    {
        $library = $this->stock->library();

        $this->assertNotNull($library);
        $this->assertEquals($this->stock->library_id, $library->id);
    }

    public function testBook(): void
    {
        $book = $this->stock->book();

        $this->assertNotNull($book);
        $this->assertEquals($this->stock->book_id, $book->id);
    }
}
