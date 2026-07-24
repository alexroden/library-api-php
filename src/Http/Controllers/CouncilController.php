<?php

namespace AlexRoden\LibraryApiPhp\Http\Controllers;

use AlexRoden\LibraryApiPhp\Bus\Commands\CreateCouncilCommand;
use AlexRoden\LibraryApiPhp\Bus\Commands\DeleteCouncilCommand;
use AlexRoden\LibraryApiPhp\Bus\Commands\UpdateCouncilCommand;
use AlexRoden\LibraryApiPhp\Database\DB;
use AlexRoden\LibraryApiPhp\Http\Exceptions\DatabaseException;
use AlexRoden\LibraryApiPhp\Http\Exceptions\InternalServiceException;
use AlexRoden\LibraryApiPhp\Http\Foundation\Request;
use AlexRoden\LibraryApiPhp\Http\Helpers\JsonResponse;
use AlexRoden\LibraryApiPhp\Http\Requests\CouncilRequest;
use AlexRoden\LibraryApiPhp\Models\Council;
use Exception;
use PDOException;


class CouncilController extends AbstractController
{
    /**
     * @throws DatabaseException
     * @throws InternalServiceException
     */
    public function create(CouncilRequest $request): JsonResponse
    {
        try {
            $user = $this->commandBus->dispatch(
                new CreateCouncilCommand(...$request->validated())
            );
        } catch (PDOException $e) {
            throw new DatabaseException($e->getMessage(), $e->getCode(), $e);
        } catch (Exception $e) {
            throw new InternalServiceException($e->getMessage());
        }

        return new JsonResponse([
            'data' => $user,
        ], 201);
    }

    /**
     * @throws DatabaseException
     * @throws InternalServiceException
     */
    public function delete(Request $request, Council $council): JsonResponse
    {
        try {
            $this->commandBus->dispatch(
                new DeleteCouncilCommand($council)
            );
        } catch (PDOException $e) {
            throw new DatabaseException($e->getMessage(), $e->getCode(), $e);
        } catch (Exception $e) {
            throw new InternalServiceException($e->getMessage());
        }

        return new JsonResponse(null, 204);
    }

    public function get(Request $request, Council $council): JsonResponse
    {
        return new JsonResponse([
            'data' => $council,
        ]);
    }

    public function list(Request $request): JsonResponse
    {
        $limit = (int) $request->input('limit', 10);
        $offset = (int) $request->input('offset', 0);
        $res = new DB(table: 'councils', attributes: ["COUNT(*) AS total"])->get(excludeModelMapping: true);
        $total = (int) $res[0]['total'];

        $councils = Council::get($limit, $offset);


        return new JsonResponse([
            'meta' => [
                'total' => $total,
                'limit' => $limit,
                'offset' => $offset,
                'count' => count($councils),
                'has_more' => ($offset + $limit) < $total,
            ],
            'data' => $councils,
        ]);
    }

    /**
     * @throws DatabaseException
     * @throws InternalServiceException
     */
    public function update(CouncilRequest $request, Council $council): JsonResponse
    {
        try {
            $council = $this->commandBus->dispatch(
                new UpdateCouncilCommand($council, ...$request->validated())
            );
        } catch (PDOException $e) {
            throw new DatabaseException($e->getMessage(), $e->getCode(), $e);
        } catch (Exception $e) {
            throw new InternalServiceException($e->getMessage());
        }

        return new JsonResponse([
            'data' => $council,
        ]);
    }
}