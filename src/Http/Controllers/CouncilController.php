<?php

namespace AlexRoden\LibraryApiPhp\Http\Controllers;

use AlexRoden\LibraryApiPhp\Bus\Commands\CreateCouncilCommand;
use AlexRoden\LibraryApiPhp\Bus\Commands\CreateUserCommand;
use AlexRoden\LibraryApiPhp\Http\Exceptions\DatabaseException;
use AlexRoden\LibraryApiPhp\Http\Exceptions\InternalServiceException;
use AlexRoden\LibraryApiPhp\Http\Foundation\Request;
use AlexRoden\LibraryApiPhp\Http\Helpers\JsonResponse;
use AlexRoden\LibraryApiPhp\Http\Requests\CreateCouncilRequest;
use AlexRoden\LibraryApiPhp\Models\Council;
use Exception;
use PDOException;


class CouncilController extends AbstractController
{
    /**
     * @throws DatabaseException
     * @throws InternalServiceException
     */
    public function create(CreateCouncilRequest $request): JsonResponse
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

    public function get(Request $request, Council $council): JsonResponse
    {
        return new JsonResponse([
            'data' => $council,
        ]);
    }

}