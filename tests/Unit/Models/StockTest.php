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

    public function testGetForLibrary(): void
    {
        $library = $this->stock->library();
        StockFactory::create(['library_id' => $library->id]);
        StockFactory::create();

        $stocks = Stock::getForLibrary($library);

        $this->assertCount(2, $stocks);
        foreach ($stocks as $stock) {
            $this->assertEquals($library->id, $stock->library_id);
        }
    }

    public function testGetForLibraryFiltersByBook(): void
    {
        $library = $this->stock->library();
        StockFactory::create(['library_id' => $library->id]);

        $stocks = Stock::getForLibrary($library, $this->stock->book_id);

        $this->assertCount(1, $stocks);
        $this->assertEquals($this->stock->id, $stocks[0]->id);
    }

    public function testGetForLibraryPaginates(): void
    {
        $library = $this->stock->library();
        StockFactory::create(['library_id' => $library->id]);

        $this->assertCount(1, Stock::getForLibrary($library, limit: 1));
        $this->assertCount(1, Stock::getForLibrary($library, limit: 1, offset: 1));
    }

    public function testGetForBook(): void
    {
        $book = $this->stock->book();
        StockFactory::create(['book_id' => $book->id]);
        StockFactory::create();

        $stocks = Stock::getForBook($book);

        $this->assertCount(2, $stocks);
        foreach ($stocks as $stock) {
            $this->assertEquals($book->id, $stock->book_id);
        }
    }

    public function testGetForBookFiltersByLibrary(): void
    {
        $book = $this->stock->book();
        StockFactory::create(['book_id' => $book->id]);

        $stocks = Stock::getForBook($book, $this->stock->library_id);

        $this->assertCount(1, $stocks);
        $this->assertEquals($this->stock->id, $stocks[0]->id);
    }

    public function testCountForLibrary(): void
    {
        $library = $this->stock->library();
        StockFactory::create(['library_id' => $library->id]);
        StockFactory::create();

        $this->assertEquals(2, Stock::countForLibrary($library));
        $this->assertEquals(1, Stock::countForLibrary($library, $this->stock->book_id));
    }

    public function testCountForBook(): void
    {
        $book = $this->stock->book();
        StockFactory::create(['book_id' => $book->id]);
        StockFactory::create();

        $this->assertEquals(2, Stock::countForBook($book));
        $this->assertEquals(1, Stock::countForBook($book, $this->stock->library_id));
    }
}
