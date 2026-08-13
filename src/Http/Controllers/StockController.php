<?php

namespace AlexRoden\LibraryApiPhp\Http\Controllers;

use AlexRoden\LibraryApiPhp\Bus\Commands\CreateStockCommand;
use AlexRoden\LibraryApiPhp\Bus\Commands\UpdateStockCommand;
use AlexRoden\LibraryApiPhp\Exceptions\UndefinedClassException;
use AlexRoden\LibraryApiPhp\Http\Exceptions\DatabaseException;
use AlexRoden\LibraryApiPhp\Http\Exceptions\InternalServiceException;
use AlexRoden\LibraryApiPhp\Http\Helpers\JsonResponse;
use AlexRoden\LibraryApiPhp\Http\Requests\CreateStockRequest;
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
}
