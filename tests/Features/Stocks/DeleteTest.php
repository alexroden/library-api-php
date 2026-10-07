<?php

namespace AlexRoden\LibraryApiPhp\Tests\Features\Stocks;

use AlexRoden\LibraryApiPhp\Authentication\Jwt;
use AlexRoden\LibraryApiPhp\Http\Exceptions\NotFoundException;
use AlexRoden\LibraryApiPhp\Http\Exceptions\PermissionException;
use AlexRoden\LibraryApiPhp\Http\Foundation\Request;
use AlexRoden\LibraryApiPhp\Models\Book;
use AlexRoden\LibraryApiPhp\Models\Library;
use AlexRoden\LibraryApiPhp\Models\Stock;
use AlexRoden\LibraryApiPhp\Tests\Factories\StockFactory;
use AlexRoden\LibraryApiPhp\Tests\Factories\UserFactory;
use AlexRoden\LibraryApiPhp\Tests\Features\AbstractFeaturesTestCase;

class DeleteTest extends AbstractFeaturesTestCase
{
    private Stock $stock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->stock = StockFactory::create();
    }

    public function testDeleteStock(): void
    {
        $this->asAuthorizedUser();

        $response = $this->handle(
            Request::create(
                method: 'DELETE',
                uri: "/api/stocks/{$this->stock->id}",
            )
        );

        $this->assertEquals(204, $response->status());
        $this->assertNull(Stock::find($this->stock->id));
    }

    public function testDeleteStockLeavesTheLibraryAndBookInPlace(): void
    {
        $this->asAuthorizedUser();

        $this->handle(
            Request::create(
                method: 'DELETE',
                uri: "/api/stocks/{$this->stock->id}",
            )
        );

        $this->assertNotNull(Library::find($this->stock->library_id));
        $this->assertNotNull(Book::find($this->stock->book_id));
    }

    public function testDeleteStockLeavesOtherStockInPlace(): void
    {
        $this->asAuthorizedUser();

        $other = StockFactory::create([
            'library_id' => $this->stock->library_id,
        ]);

        $this->handle(
            Request::create(
                method: 'DELETE',
                uri: "/api/stocks/{$this->stock->id}",
            )
        );

        $this->assertNull(Stock::find($this->stock->id));
        $this->assertNotNull(Stock::find($other->id));
    }

    public function testDeleteStockNotFound(): void
    {
        $this->asAuthorizedUser();

        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage(Stock::class.' not found');

        $this->handle(
            Request::create(
                method: 'DELETE',
                uri: '/api/stocks/'.($this->stock->id + 1),
            )
        );
    }

    public function testDeleteStockRequiresTheStocksDeletePermission(): void
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
                    method: 'DELETE',
                    uri: "/api/stocks/{$this->stock->id}",
                )
            );
        } finally {
            $this->assertNotNull(Stock::find($this->stock->id));
        }
    }
}
