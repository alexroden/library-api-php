<?php

namespace AlexRoden\LibraryApiPhp\Tests\Features\Stocks;

use AlexRoden\LibraryApiPhp\Authentication\Jwt;
use AlexRoden\LibraryApiPhp\Http\Exceptions\NotFoundException;
use AlexRoden\LibraryApiPhp\Http\Exceptions\PermissionException;
use AlexRoden\LibraryApiPhp\Http\Foundation\Request;
use AlexRoden\LibraryApiPhp\Models\Book;
use AlexRoden\LibraryApiPhp\Models\Library;
use AlexRoden\LibraryApiPhp\Models\Stock;
use AlexRoden\LibraryApiPhp\Tests\Factories\BookFactory;
use AlexRoden\LibraryApiPhp\Tests\Factories\LibraryFactory;
use AlexRoden\LibraryApiPhp\Tests\Factories\StockFactory;
use AlexRoden\LibraryApiPhp\Tests\Factories\UserFactory;
use AlexRoden\LibraryApiPhp\Tests\Features\AbstractFeaturesTestCase;

class ListTest extends AbstractFeaturesTestCase
{
    private Library $library;
    private Book $book;

    /**
     * The library and book share one stock record; each also has stock with a
     * different counterpart, and an unrelated record proves the scoping.
     */
    private Stock $libraryAndBookStock;
    private Stock $libraryOnlyStock;
    private Stock $bookOnlyStock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->library = LibraryFactory::create();
        $this->book = BookFactory::create();

        $this->libraryAndBookStock = StockFactory::create([
            'library_id' => $this->library->id,
            'book_id' => $this->book->id,
        ]);
        $this->libraryOnlyStock = StockFactory::create([
            'library_id' => $this->library->id,
        ]);
        $this->bookOnlyStock = StockFactory::create([
            'book_id' => $this->book->id,
        ]);
        StockFactory::create();
    }

    public function testListStockForLibrary(): void
    {
        $this->asAuthorizedUser();

        $response = $this->handle(
            Request::create(
                method: 'GET',
                uri: "/api/libraries/{$this->library->id}/stocks",
            )
        );

        $this->assertEquals(200, $response->status());
        $this->assertEquals(
            [
                'total' => 2,
                'limit' => 10,
                'offset' => 0,
                'count' => 2,
                'has_more' => false,
            ],
            $response->json()['meta']
        );
        $this->assertSame(
            [$this->libraryAndBookStock->id, $this->libraryOnlyStock->id],
            $this->ids($response->json()['data'])
        );
    }

    public function testListStockForLibraryPaginates(): void
    {
        $this->asAuthorizedUser();

        $response = $this->handle(
            Request::create(
                method: 'GET',
                uri: "/api/libraries/{$this->library->id}/stocks",
                query: [
                    'limit' => 1,
                ],
            )
        );

        $this->assertEquals(2, $response->json()['meta']['total']);
        $this->assertEquals(1, $response->json()['meta']['count']);
        $this->assertTrue($response->json()['meta']['has_more']);
    }

    public function testListStockForLibraryFilteredByBook(): void
    {
        $this->asAuthorizedUser();

        $response = $this->handle(
            Request::create(
                method: 'GET',
                uri: "/api/libraries/{$this->library->id}/stocks",
                query: [
                    'book_id' => $this->book->id,
                ],
            )
        );

        $this->assertEquals(200, $response->status());
        $this->assertEquals(1, $response->json()['meta']['total']);
        $this->assertSame(
            [$this->libraryAndBookStock->id],
            $this->ids($response->json()['data'])
        );
    }

    public function testListStockForLibraryFilteredByBookWithNoStockIsEmpty(): void
    {
        $this->asAuthorizedUser();

        $response = $this->handle(
            Request::create(
                method: 'GET',
                uri: "/api/libraries/{$this->library->id}/stocks",
                query: [
                    'book_id' => BookFactory::create()->id,
                ],
            )
        );

        $this->assertEquals(200, $response->status());
        $this->assertEquals(0, $response->json()['meta']['total']);
        $this->assertSame([], $response->json()['data']);
    }

    public function testListStockForLibraryThatDoesNotExist(): void
    {
        $this->asAuthorizedUser();

        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage(Library::class.' not found');

        $this->handle(
            Request::create(
                method: 'GET',
                uri: '/api/libraries/'.($this->library->id + 1000).'/stocks',
            )
        );
    }

    public function testListStockForBook(): void
    {
        $this->asAuthorizedUser();

        $response = $this->handle(
            Request::create(
                method: 'GET',
                uri: "/api/books/{$this->book->id}/stocks",
            )
        );

        $this->assertEquals(200, $response->status());
        $this->assertEquals(2, $response->json()['meta']['total']);
        $this->assertSame(
            [$this->libraryAndBookStock->id, $this->bookOnlyStock->id],
            $this->ids($response->json()['data'])
        );
    }

    public function testListStockForBookFilteredByLibrary(): void
    {
        $this->asAuthorizedUser();

        $response = $this->handle(
            Request::create(
                method: 'GET',
                uri: "/api/books/{$this->book->id}/stocks",
                query: [
                    'library_id' => $this->library->id,
                ],
            )
        );

        $this->assertEquals(200, $response->status());
        $this->assertEquals(1, $response->json()['meta']['total']);
        $this->assertSame(
            [$this->libraryAndBookStock->id],
            $this->ids($response->json()['data'])
        );
    }

    public function testListStockForBookFilteredByLibraryWithNoStockIsEmpty(): void
    {
        $this->asAuthorizedUser();

        $response = $this->handle(
            Request::create(
                method: 'GET',
                uri: "/api/books/{$this->book->id}/stocks",
                query: [
                    'library_id' => LibraryFactory::create()->id,
                ],
            )
        );

        $this->assertEquals(200, $response->status());
        $this->assertEquals(0, $response->json()['meta']['total']);
        $this->assertSame([], $response->json()['data']);
    }

    public function testListStockForBookThatDoesNotExist(): void
    {
        $this->asAuthorizedUser();

        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage(Book::class.' not found');

        $this->handle(
            Request::create(
                method: 'GET',
                uri: '/api/books/'.($this->book->id + 1000).'/stocks',
            )
        );
    }

    public function testListStockForLibraryRequiresTheStocksListPermission(): void
    {
        $this->withoutPermissions();

        $this->expectException(PermissionException::class);

        $this->handle(
            Request::create(
                method: 'GET',
                uri: "/api/libraries/{$this->library->id}/stocks",
            )
        );
    }

    public function testListStockForBookRequiresTheStocksListPermission(): void
    {
        $this->withoutPermissions();

        $this->expectException(PermissionException::class);

        $this->handle(
            Request::create(
                method: 'GET',
                uri: "/api/books/{$this->book->id}/stocks",
            )
        );
    }

    private function withoutPermissions(): void
    {
        $user = UserFactory::create();
        $this->createRolesAndPermissions();

        $jwt = new Jwt(env('JWT_SECRET'));
        $this->headers['Authorization'] = 'Bearer ' . $jwt->encode([
            'sub' => $user->id,
            'email' => $user->email,
            'permissions' => [],
        ]);
    }

    /**
     * @param array<Stock> $stocks
     *
     * @return array<int>
     */
    private function ids(array $stocks): array
    {
        return array_map(fn (Stock $stock) => $stock->id, $stocks);
    }
}
