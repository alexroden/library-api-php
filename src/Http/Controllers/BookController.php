<?php

namespace AlexRoden\LibraryApiPhp\Http\Controllers;

use AlexRoden\LibraryApiPhp\Bus\Commands\CreateBookCommand;
use AlexRoden\LibraryApiPhp\Bus\Commands\DeleteBookCommand;
use AlexRoden\LibraryApiPhp\Bus\Commands\UpdateBookCommand;
use AlexRoden\LibraryApiPhp\Database\DB;
use AlexRoden\LibraryApiPhp\Exceptions\UndefinedClassException;
use AlexRoden\LibraryApiPhp\Http\Exceptions\DatabaseException;
use AlexRoden\LibraryApiPhp\Http\Exceptions\InternalServiceException;
use AlexRoden\LibraryApiPhp\Http\Foundation\Request;
use AlexRoden\LibraryApiPhp\Http\Helpers\JsonResponse;
use AlexRoden\LibraryApiPhp\Http\Requests\CreateBookRequest;
use AlexRoden\LibraryApiPhp\Http\Requests\UpdateBookRequest;
use AlexRoden\LibraryApiPhp\Models\Book;
use Exception;
use PDOException;

class BookController extends AbstractController
{
    /**
     * @throws DatabaseException
     * @throws InternalServiceException
     */
    public function create(CreateBookRequest $request): JsonResponse
    {
        try {
            $category = $this->commandBus->dispatch(
                new CreateBookCommand(...$request->validated())
            );
        } catch (PDOException $e) {
            throw new DatabaseException($e->getMessage(), $e->getCode(), $e);
        } catch (Exception $e) {
            throw new InternalServiceException($e->getMessage());
        }

        return new JsonResponse([
            'data' => $category,
        ], 201);
    }

    /**
     * @throws DatabaseException
     * @throws InternalServiceException
     */
    public function delete(Request $request, Book $book): JsonResponse
    {
        try {
            $this->commandBus->dispatch(
                new DeleteBookCommand($book)
            );
        } catch (PDOException $e) {
            throw new DatabaseException($e->getMessage(), $e->getCode(), $e);
        } catch (Exception $e) {
            throw new InternalServiceException($e->getMessage());
        }

        return new JsonResponse(null, 204);
    }

    public function get(Request $request, Book $book): JsonResponse
    {
        return new JsonResponse([
            'data' => $book,
        ]);
    }

    /**
     * @throws UndefinedClassException
     */
    public function list(Request $request): JsonResponse
    {
        $limit = (int) $request->input('limit', 10);
        $offset = (int) $request->input('offset', 0);
        $res = new DB(table: 'books', attributes: ["COUNT(*) AS total"])->get(excludeModelMapping: true);
        $total = (int) $res[0]['total'];

        $books = Book::get($limit, $offset);


        return new JsonResponse([
            'meta' => [
                'total' => $total,
                'limit' => $limit,
                'offset' => $offset,
                'count' => count($books),
                'has_more' => ($offset + $limit) < $total,
            ],
            'data' => $books,
        ]);
    }

    /**
     * @throws DatabaseException
     * @throws InternalServiceException
     */
    public function update(UpdateBookRequest $request, Book $book): JsonResponse
    {
        try {
            $book = $this->commandBus->dispatch(
                new UpdateBookCommand($book, ...$request->validated())
            );
        } catch (PDOException $e) {
            throw new DatabaseException($e->getMessage(), $e->getCode(), $e);
        } catch (Exception $e) {
            throw new InternalServiceException($e->getMessage());
        }

        return new JsonResponse([
            'data' => $book,
        ]);
    }
}