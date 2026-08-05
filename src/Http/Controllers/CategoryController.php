<?php

namespace AlexRoden\LibraryApiPhp\Http\Controllers;

use AlexRoden\LibraryApiPhp\Bus\Commands\CreateCategoryCommand;
use AlexRoden\LibraryApiPhp\Bus\Commands\DeleteCategoryCommand;
use AlexRoden\LibraryApiPhp\Bus\Commands\UpdateCategoryCommand;
use AlexRoden\LibraryApiPhp\Database\DB;
use AlexRoden\LibraryApiPhp\Exceptions\UndefinedClassException;
use AlexRoden\LibraryApiPhp\Http\Exceptions\DatabaseException;
use AlexRoden\LibraryApiPhp\Http\Exceptions\InternalServiceException;
use AlexRoden\LibraryApiPhp\Http\Foundation\Request;
use AlexRoden\LibraryApiPhp\Http\Helpers\JsonResponse;
use AlexRoden\LibraryApiPhp\Http\Requests\CategoryRequest;
use AlexRoden\LibraryApiPhp\Models\Category;
use Exception;
use PDOException;


class CategoryController extends AbstractController
{
    /**
     * @throws DatabaseException
     * @throws InternalServiceException
     */
    public function create(CategoryRequest $request): JsonResponse
    {
        try {
            $category = $this->commandBus->dispatch(
                new CreateCategoryCommand(...$request->validated())
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
    public function delete(Request $request, Category $category): JsonResponse
    {
        try {
            $this->commandBus->dispatch(
                new DeleteCategoryCommand($category)
            );
        } catch (PDOException $e) {
            throw new DatabaseException($e->getMessage(), $e->getCode(), $e);
        } catch (Exception $e) {
            throw new InternalServiceException($e->getMessage());
        }

        return new JsonResponse(null, 204);
    }

    public function get(Request $request, Category $category): JsonResponse
    {
        return new JsonResponse([
            'data' => $category,
        ]);
    }

    /**
     * @throws UndefinedClassException
     */
    public function list(Request $request): JsonResponse
    {
        $limit = (int) $request->input('limit', 10);
        $offset = (int) $request->input('offset', 0);
        $res = new DB(table: 'categories', attributes: ["COUNT(*) AS total"])->get(excludeModelMapping: true);
        $total = (int) $res[0]['total'];

        $categories = Category::get($limit, $offset);


        return new JsonResponse([
            'meta' => [
                'total' => $total,
                'limit' => $limit,
                'offset' => $offset,
                'count' => count($categories),
                'has_more' => ($offset + $limit) < $total,
            ],
            'data' => $categories,
        ]);
    }

    /**
     * @throws DatabaseException
     * @throws InternalServiceException
     */
    public function update(CategoryRequest $request, Category $category): JsonResponse
    {
        try {
            $category = $this->commandBus->dispatch(
                new UpdateCategoryCommand($category, ...$request->validated())
            );
        } catch (PDOException $e) {
            throw new DatabaseException($e->getMessage(), $e->getCode(), $e);
        } catch (Exception $e) {
            throw new InternalServiceException($e->getMessage());
        }

        return new JsonResponse([
            'data' => $category,
        ]);
    }
}