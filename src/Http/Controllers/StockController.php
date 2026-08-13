<?php

namespace AlexRoden\LibraryApiPhp\Http\Controllers;

use AlexRoden\LibraryApiPhp\Bus\Commands\CreateStockCommand;
use AlexRoden\LibraryApiPhp\Http\Exceptions\DatabaseException;
use AlexRoden\LibraryApiPhp\Http\Exceptions\InternalServiceException;
use AlexRoden\LibraryApiPhp\Http\Helpers\JsonResponse;
use AlexRoden\LibraryApiPhp\Http\Requests\CreateStockRequest;
use Exception;
use PDOException;

class StockController extends AbstractController
{
    /**
     * @throws DatabaseException
     * @throws InternalServiceException
     */
    public function create(CreateStockRequest $request): JsonResponse
    {
        try {
            $stock = $this->commandBus->dispatch(
                new CreateStockCommand(...$request->validated())
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
