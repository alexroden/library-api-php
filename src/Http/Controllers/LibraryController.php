<?php

namespace AlexRoden\LibraryApiPhp\Http\Controllers;

use AlexRoden\LibraryApiPhp\Bus\Commands\CreateLibraryCommand;
use AlexRoden\LibraryApiPhp\Bus\Commands\DeleteLibraryCommand;
use AlexRoden\LibraryApiPhp\Bus\Commands\UpdateLibraryCommand;
use AlexRoden\LibraryApiPhp\Database\DB;
use AlexRoden\LibraryApiPhp\Exceptions\UndefinedClassException;
use AlexRoden\LibraryApiPhp\Http\Exceptions\DatabaseException;
use AlexRoden\LibraryApiPhp\Http\Exceptions\InternalServiceException;
use AlexRoden\LibraryApiPhp\Http\Foundation\Request;
use AlexRoden\LibraryApiPhp\Http\Helpers\JsonResponse;
use AlexRoden\LibraryApiPhp\Http\Requests\CouncilRequest;
use AlexRoden\LibraryApiPhp\Http\Requests\LibraryRequest;
use AlexRoden\LibraryApiPhp\Models\Library;
use Exception;
use PDOException;


class LibraryController extends AbstractController
{
    /**
     * @throws DatabaseException
     * @throws InternalServiceException
     */
    public function create(LibraryRequest $request): JsonResponse
    {
        try {
            $library = $this->commandBus->dispatch(
                new CreateLibraryCommand(...$request->validated())
            );
        } catch (PDOException $e) {
            throw new DatabaseException($e->getMessage(), $e->getCode(), $e);
        } catch (Exception $e) {
            throw new InternalServiceException($e->getMessage());
        }

        return new JsonResponse([
            'data' => $library,
        ], 201);
    }

    /**
     * @throws DatabaseException
     * @throws InternalServiceException
     */
    public function delete(Request $request, Library $library): JsonResponse
    {
        try {
            $this->commandBus->dispatch(
                new DeleteLibraryCommand($library)
            );
        } catch (PDOException $e) {
            throw new DatabaseException($e->getMessage(), $e->getCode(), $e);
        } catch (Exception $e) {
            throw new InternalServiceException($e->getMessage());
        }

        return new JsonResponse(null, 204);
    }

    public function get(Request $request, Library $library): JsonResponse
    {
        return new JsonResponse([
            'data' => $library,
        ]);
    }

    /**
     * @throws UndefinedClassException
     */
    public function list(Request $request): JsonResponse
    {
        $limit = (int) $request->input('limit', 10);
        $offset = (int) $request->input('offset', 0);
        $res = new DB(table: 'libraries', attributes: ["COUNT(*) AS total"])->get(excludeModelMapping: true);
        $total = (int) $res[0]['total'];

        $libraries = Library::get($limit, $offset);


        return new JsonResponse([
            'meta' => [
                'total' => $total,
                'limit' => $limit,
                'offset' => $offset,
                'count' => count($libraries),
                'has_more' => ($offset + $limit) < $total,
            ],
            'data' => $libraries,
        ]);
    }

    /**
     * @throws DatabaseException
     * @throws InternalServiceException
     */
    public function update(LibraryRequest $request, Library $library): JsonResponse
    {
        try {
            $library = $this->commandBus->dispatch(
                new UpdateLibraryCommand($library, ...$request->validated())
            );
        } catch (PDOException $e) {
            throw new DatabaseException($e->getMessage(), $e->getCode(), $e);
        } catch (Exception $e) {
            throw new InternalServiceException($e->getMessage());
        }

        return new JsonResponse([
            'data' => $library,
        ]);
    }
}
