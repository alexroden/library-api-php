<?php

namespace AlexRoden\LibraryApiPhp\Tests\Features\Stocks;

use AlexRoden\LibraryApiPhp\Authentication\Jwt;
use AlexRoden\LibraryApiPhp\Http\Exceptions\PermissionException;
use AlexRoden\LibraryApiPhp\Http\Exceptions\ValidationException;
use AlexRoden\LibraryApiPhp\Http\Foundation\Request;
use AlexRoden\LibraryApiPhp\Models\Book;
use AlexRoden\LibraryApiPhp\Models\Library;
use AlexRoden\LibraryApiPhp\Models\Stock;
use AlexRoden\LibraryApiPhp\Tests\Factories\BookFactory;
use AlexRoden\LibraryApiPhp\Tests\Factories\LibraryFactory;
use AlexRoden\LibraryApiPhp\Tests\Factories\UserFactory;
use AlexRoden\LibraryApiPhp\Tests\Features\AbstractFeaturesTestCase;

class CreateTest extends AbstractFeaturesTestCase
{
    private Library $library;
    private Book $book;

    protected function setUp(): void
    {
        parent::setUp();

        $this->library = LibraryFactory::create();
        $this->book = BookFactory::create();
    }

    public function testCreateStock(): void
    {
        $this->asAuthorizedUser();

        $response = $this->handle(
            Request::create(
                method: 'POST',
                uri: '/api/stocks',
                body: [
                    'library_id' => $this->library->id,
                    'book_id' => $this->book->id,
                    'quantity' => 12,
                ]
            )
        );

        $this->assertEquals(201, $response->status());

        $stock = Stock::where('library_id', '=', $this->library->id)->first();
        $this->assertNotNull($stock);
        $this->assertEquals($this->book->id, $stock->book_id);
        $this->assertEquals(12, $stock->quantity);
    }

    public function testCreateStockDefaultsQuantityToZero(): void
    {
        $this->asAuthorizedUser();

        $response = $this->handle(
            Request::create(
                method: 'POST',
                uri: '/api/stocks',
                body: [
                    'library_id' => $this->library->id,
                    'book_id' => $this->book->id,
                ]
            )
        );

        $this->assertEquals(201, $response->status());

        $stock = Stock::where('library_id', '=', $this->library->id)->first();
        $this->assertNotNull($stock);
        $this->assertEquals(0, $stock->quantity);
    }

    public function testCreateStockUpdatesAnExistingRecord(): void
    {
        $this->asAuthorizedUser();

        $existing = Stock::create([
            'library_id' => $this->library->id,
            'book_id' => $this->book->id,
            'quantity' => 5,
        ]);

        $response = $this->handle(
            Request::create(
                method: 'POST',
                uri: '/api/stocks',
                body: [
                    'library_id' => $this->library->id,
                    'book_id' => $this->book->id,
                    'quantity' => 12,
                ]
            )
        );

        $this->assertEquals(201, $response->status());

        $stocks = Stock::where('library_id', '=', $this->library->id)->get();
        $this->assertCount(1, $stocks);
        $this->assertEquals($existing->id, $stocks[0]->id);
        $this->assertEquals(12, $stocks[0]->quantity);
    }

    public function testCreateStockIsScopedToTheLibraryAndBookPair(): void
    {
        $this->asAuthorizedUser();

        $otherBook = BookFactory::create();
        Stock::create([
            'library_id' => $this->library->id,
            'book_id' => $otherBook->id,
            'quantity' => 5,
        ]);

        $response = $this->handle(
            Request::create(
                method: 'POST',
                uri: '/api/stocks',
                body: [
                    'library_id' => $this->library->id,
                    'book_id' => $this->book->id,
                    'quantity' => 12,
                ]
            )
        );

        $this->assertEquals(201, $response->status());

        $this->assertCount(2, Stock::where('library_id', '=', $this->library->id)->get());
        $this->assertEquals(
            5,
            Stock::findByLibraryAndBook($this->library->id, $otherBook->id)->quantity
        );
        $this->assertEquals(
            12,
            Stock::findByLibraryAndBook($this->library->id, $this->book->id)->quantity
        );
    }

    public function testCreateStockRequiresALibraryAndABook(): void
    {
        $this->asAuthorizedUser();

        /*
         * index.php is the only error boundary, so a validation failure leaves
         * the request as an exception rather than a response.
         */
        try {
            $this->handle(
                Request::create(
                    method: 'POST',
                    uri: '/api/stocks',
                    body: [
                        'quantity' => 12,
                    ]
                )
            );

            $this->fail('Expected the request to fail validation.');
        } catch (ValidationException $e) {
            $this->assertEquals(422, $e->statusCode());
            $this->assertArrayHasKey('library_id', $e->errors());
            $this->assertArrayHasKey('book_id', $e->errors());
        }

        $this->assertNull(Stock::where('book_id', '=', $this->book->id)->first());
    }

    /**
     * The other feature tests authorise as a super admin, which cannot catch a
     * route that is missing its permission middleware, so mint a token without
     * `stocks.create` and drive the same route with it.
     */
    public function testCreateStockRequiresTheStocksCreatePermission(): void
    {
        $user = UserFactory::create();
        $this->createRolesAndPermissions();

        $jwt = new Jwt(env('JWT_SECRET'));
        $this->headers['Authorization'] = 'Bearer ' . $jwt->encode([
            'sub' => $user->id,
            'email' => $user->email,
            'permissions' => [],
        ]);

        $this->expectException(PermissionException::class);

        try {
            $this->handle(
                Request::create(
                    method: 'POST',
                    uri: '/api/stocks',
                    body: [
                        'library_id' => $this->library->id,
                        'book_id' => $this->book->id,
                        'quantity' => 12,
                    ]
                )
            );
        } finally {
            $this->assertNull(Stock::where('book_id', '=', $this->book->id)->first());
        }
    }
}
