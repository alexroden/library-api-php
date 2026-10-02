<?php

namespace AlexRoden\LibraryApiPhp\Http\Controllers;

use AlexRoden\LibraryApiPhp\Bus\Commands\CreateStockCommand;
use AlexRoden\LibraryApiPhp\Bus\Commands\UpdateStockCommand;
use AlexRoden\LibraryApiPhp\Exceptions\UndefinedClassException;
use AlexRoden\LibraryApiPhp\Http\Exceptions\DatabaseException;
use AlexRoden\LibraryApiPhp\Http\Exceptions\InternalServiceException;
use AlexRoden\LibraryApiPhp\Http\Foundation\Request;
use AlexRoden\LibraryApiPhp\Http\Helpers\JsonResponse;
use AlexRoden\LibraryApiPhp\Http\Requests\CreateStockRequest;
use AlexRoden\LibraryApiPhp\Models\Book;
use AlexRoden\LibraryApiPhp\Models\Library;
use AlexRoden\LibraryApiPhp\Models\Stock;
use Exception;
use PDOException;

class StockController extends AbstractController
{
    /**
     * A library can only hold one stock record per book, so an existing record
     * for the pair is updated to the submitted quantity rather than rejected by
     * the unique constraint.
     *
     * @throws DatabaseException
     * @throws InternalServiceException
     * @throws UndefinedClassException
     */
    public function create(CreateStockRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $existing = Stock::findByLibraryAndBook(
            (int) $validated['libraryId'],
            (int) $validated['bookId'],
        );

        try {
            $stock = $existing
                ? $this->commandBus->dispatch(
                    new UpdateStockCommand($existing, (int) ($validated['quantity'] ?? 0))
                )
                : $this->commandBus->dispatch(
                    new CreateStockCommand(...$validated)
                );
        } catch (PDOException $e) {
            throw new DatabaseException($e->getMessage(), $e->getCode(), $e);
        } catch (Exception $e) {
            throw new InternalServiceException($e->getMessage());
        }

        return new JsonResponse([
            'data' => $stock,
        ], 201);
    }

    public function get(Request $request, Stock $stock): JsonResponse
    {
        return new JsonResponse([
            'data' => $stock,
        ]);
    }

    /**
     * @throws UndefinedClassException
     */
    public function listForLibrary(Request $request, Library $library): JsonResponse
    {
        $limit = (int) $request->input('limit', 10);
        $offset = (int) $request->input('offset', 0);
        $bookId = $this->optionalId($request, 'book_id');

        return $this->paginated(
            Stock::getForLibrary($library, $bookId, $limit, $offset),
            Stock::countForLibrary($library, $bookId),
            $limit,
            $offset,
        );
    }

    /**
     * @throws UndefinedClassException
     */
    public function listForBook(Request $request, Book $book): JsonResponse
    {
        $limit = (int) $request->input('limit', 10);
        $offset = (int) $request->input('offset', 0);
        $libraryId = $this->optionalId($request, 'library_id');

        return $this->paginated(
            Stock::getForBook($book, $libraryId, $limit, $offset),
            Stock::countForBook($book, $libraryId),
            $limit,
            $offset,
        );
    }

    private function optionalId(Request $request, string $key): ?int
    {
        $id = $request->input($key);

        return $id === null ? null : (int) $id;
    }

    /**
     * @param array<Stock> $stocks
     */
    private function paginated(array $stocks, int $total, int $limit, int $offset): JsonResponse
    {
        return new JsonResponse([
            'meta' => [
                'total' => $total,
                'limit' => $limit,
                'offset' => $offset,
                'count' => count($stocks),
                'has_more' => ($offset + $limit) < $total,
            ],
            'data' => $stocks,
        ]);
    }
}
