<?php

namespace AlexRoden\LibraryApiPhp\Http\Controllers;

use AlexRoden\LibraryApiPhp\Bus\Commands\CreateAuthorCommand;
use AlexRoden\LibraryApiPhp\Bus\Commands\DeleteAuthorCommand;
use AlexRoden\LibraryApiPhp\Bus\Commands\UpdateAuthorCommand;
use AlexRoden\LibraryApiPhp\Database\DB;
use AlexRoden\LibraryApiPhp\Exceptions\UndefinedClassException;
use AlexRoden\LibraryApiPhp\Http\Exceptions\DatabaseException;
use AlexRoden\LibraryApiPhp\Http\Exceptions\InternalServiceException;
use AlexRoden\LibraryApiPhp\Http\Foundation\Request;
use AlexRoden\LibraryApiPhp\Http\Helpers\JsonResponse;
use AlexRoden\LibraryApiPhp\Http\Requests\CreateAuthorRequest;
use AlexRoden\LibraryApiPhp\Http\Requests\UpdateAuthorRequest;
use AlexRoden\LibraryApiPhp\Models\Author;
use Exception;
use PDOException;


class AuthorController extends AbstractController
{
    /**
     * @throws DatabaseException
     * @throws InternalServiceException
     */
    public function create(CreateAuthorRequest $request): JsonResponse
    {
        try {
            $library = $this->commandBus->dispatch(
                new CreateAuthorCommand(...$request->validated())
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
    public function delete(Request $request, Author $author): JsonResponse
    {
        try {
            $this->commandBus->dispatch(
                new DeleteAuthorCommand($author)
            );
        } catch (PDOException $e) {
            throw new DatabaseException($e->getMessage(), $e->getCode(), $e);
        } catch (Exception $e) {
            throw new InternalServiceException($e->getMessage());
        }

        return new JsonResponse(null, 204);
    }

    public function get(Request $request, Author $author): JsonResponse
    {
        return new JsonResponse([
            'data' => $author,
        ]);
    }

    /**
     * @throws UndefinedClassException
     */
    public function list(Request $request): JsonResponse
    {
        $limit = (int) $request->input('limit', 10);
        $offset = (int) $request->input('offset', 0);
        $res = new DB(table: 'authors', attributes: ["COUNT(*) AS total"])->get(excludeModelMapping: true);
        $total = (int) $res[0]['total'];

        $author = Author::get($limit, $offset);


        return new JsonResponse([
            'meta' => [
                'total' => $total,
                'limit' => $limit,
                'offset' => $offset,
                'count' => count($author),
                'has_more' => ($offset + $limit) < $total,
            ],
            'data' => $author,
        ]);
    }

    /**
     * @throws DatabaseException
     * @throws InternalServiceException
     */
    public function update(UpdateAuthorRequest $request, Author $author): JsonResponse
    {
        try {
            $author = $this->commandBus->dispatch(
                new UpdateAuthorCommand($author, ...$request->validated())
            );
        } catch (PDOException $e) {
            throw new DatabaseException($e->getMessage(), $e->getCode(), $e);
        } catch (Exception $e) {
            throw new InternalServiceException($e->getMessage());
        }

        return new JsonResponse([
            'data' => $author,
        ]);
    }
}
