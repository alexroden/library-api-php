<?php

namespace AlexRoden\LibraryApiPhp\Tests\Features\Stocks;

use AlexRoden\LibraryApiPhp\Authentication\Jwt;
use AlexRoden\LibraryApiPhp\Http\Exceptions\NotFoundException;
use AlexRoden\LibraryApiPhp\Http\Exceptions\PermissionException;
use AlexRoden\LibraryApiPhp\Http\Foundation\Request;
use AlexRoden\LibraryApiPhp\Models\Stock;
use AlexRoden\LibraryApiPhp\Tests\Factories\StockFactory;
use AlexRoden\LibraryApiPhp\Tests\Factories\UserFactory;
use AlexRoden\LibraryApiPhp\Tests\Features\AbstractFeaturesTestCase;

class GetTest extends AbstractFeaturesTestCase
{
    private Stock $stock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->stock = StockFactory::create();
    }

    public function testGetStock(): void
    {
        $this->asAuthorizedUser();

        $response = $this->handle(
            Request::create(
                method: 'GET',
                uri: "/api/stocks/{$this->stock->id}",
            )
        );

        $this->assertEquals(200, $response->status());
        $this->assertSame(
            $this->stock->toArray(),
            $response->json()['data']->toArray()
        );
    }

    public function testGetStockThatDoesNotExist(): void
    {
        $this->asAuthorizedUser();

        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage(Stock::class.' not found');

        $this->handle(
            Request::create(
                method: 'GET',
                uri: '/api/stocks/'.($this->stock->id + 1),
            )
        );
    }

    public function testGetStockRequiresTheStocksGetPermission(): void
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

        $this->handle(
            Request::create(
                method: 'GET',
                uri: "/api/stocks/{$this->stock->id}",
            )
        );
    }
}
