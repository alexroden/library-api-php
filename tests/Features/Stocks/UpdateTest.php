<?php

namespace AlexRoden\LibraryApiPhp\Tests\Features\Stocks;

use AlexRoden\LibraryApiPhp\Authentication\Jwt;
use AlexRoden\LibraryApiPhp\Http\Exceptions\NotFoundException;
use AlexRoden\LibraryApiPhp\Http\Exceptions\PermissionException;
use AlexRoden\LibraryApiPhp\Http\Exceptions\ValidationException;
use AlexRoden\LibraryApiPhp\Http\Foundation\Request;
use AlexRoden\LibraryApiPhp\Models\Stock;
use AlexRoden\LibraryApiPhp\Tests\Factories\BookFactory;
use AlexRoden\LibraryApiPhp\Tests\Factories\LibraryFactory;
use AlexRoden\LibraryApiPhp\Tests\Factories\StockFactory;
use AlexRoden\LibraryApiPhp\Tests\Factories\UserFactory;
use AlexRoden\LibraryApiPhp\Tests\Features\AbstractFeaturesTestCase;

class UpdateTest extends AbstractFeaturesTestCase
{
    private Stock $stock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->stock = StockFactory::create(['quantity' => 5]);
    }

    public function testUpdateStock(): void
    {
        $this->asAuthorizedUser();

        $response = $this->handle(
            Request::create(
                method: 'PUT',
                uri: "/api/stocks/{$this->stock->id}",
                body: [
                    'quantity' => 12,
                ]
            )
        );

        $this->assertEquals(200, $response->status());
        $this->assertEquals(12, Stock::find($this->stock->id)->quantity);
    }

    public function testUpdateStockToZero(): void
    {
        $this->asAuthorizedUser();

        $response = $this->handle(
            Request::create(
                method: 'PUT',
                uri: "/api/stocks/{$this->stock->id}",
                body: [
                    'quantity' => 0,
                ]
            )
        );

        $this->assertEquals(200, $response->status());
        $this->assertEquals(0, Stock::find($this->stock->id)->quantity);
    }

    public function testUpdateStockKeepsTheLibraryAndBookPair(): void
    {
        $this->asAuthorizedUser();

        $otherLibrary = LibraryFactory::create();
        $otherBook = BookFactory::create();

        $response = $this->handle(
            Request::create(
                method: 'PUT',
                uri: "/api/stocks/{$this->stock->id}",
                body: [
                    'library_id' => $otherLibrary->id,
                    'book_id' => $otherBook->id,
                    'quantity' => 12,
                ]
            )
        );

        $this->assertEquals(200, $response->status());

        $stock = Stock::find($this->stock->id);
        $this->assertEquals($this->stock->library_id, $stock->library_id);
        $this->assertEquals($this->stock->book_id, $stock->book_id);
        $this->assertEquals(12, $stock->quantity);
    }

    public function testUpdateStockRejectsAnInvalidQuantity(): void
    {
        $this->asAuthorizedUser();

        try {
            $this->handle(
                Request::create(
                    method: 'PUT',
                    uri: "/api/stocks/{$this->stock->id}",
                    body: [
                        'quantity' => -1,
                    ]
                )
            );

            $this->fail('Expected the request to fail validation.');
        } catch (ValidationException $e) {
            $this->assertEquals(422, $e->statusCode());
            $this->assertArrayHasKey('quantity', $e->errors());
        }

        $this->assertEquals(5, Stock::find($this->stock->id)->quantity);
    }

    public function testUpdateStockNotFound(): void
    {
        $this->asAuthorizedUser();

        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage(Stock::class.' not found');

        $this->handle(
            Request::create(
                method: 'PUT',
                uri: '/api/stocks/'.($this->stock->id + 1),
                body: [
                    'quantity' => 12,
                ]
            )
        );
    }

    public function testUpdateStockRequiresTheStocksUpdatePermission(): void
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
                    method: 'PUT',
                    uri: "/api/stocks/{$this->stock->id}",
                    body: [
                        'quantity' => 12,
                    ]
                )
            );
        } finally {
            $this->assertEquals(5, Stock::find($this->stock->id)->quantity);
        }
    }
}
