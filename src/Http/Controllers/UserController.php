<?php

namespace AlexRoden\LibraryApiPhp\Http\Controllers;

use AlexRoden\LibraryApiPhp\Authentication\Jwt;
use AlexRoden\LibraryApiPhp\Bus\CommandBus;
use AlexRoden\LibraryApiPhp\Bus\Commands\CreateUserCommand;
use AlexRoden\LibraryApiPhp\Database\DB;
use AlexRoden\LibraryApiPhp\Exceptions\UndefinedClassException;
use AlexRoden\LibraryApiPhp\Http\Exceptions\DatabaseException;
use AlexRoden\LibraryApiPhp\Http\Exceptions\InternalServiceException;
use AlexRoden\LibraryApiPhp\Http\Exceptions\NotFoundException;
use AlexRoden\LibraryApiPhp\Http\Exceptions\UnauthorizedException;
use AlexRoden\LibraryApiPhp\Http\Foundation\Request;
use AlexRoden\LibraryApiPhp\Http\Helpers\JsonResponse;
use AlexRoden\LibraryApiPhp\Http\Requests\AuthRequest;
use AlexRoden\LibraryApiPhp\Http\Requests\CreateUserRequest;
use AlexRoden\LibraryApiPhp\Models\User;
use Exception;
use JsonException;
use PDOException;


class UserController
{
    protected Jwt $jwt;

    public function __construct(
        private readonly CommandBus $commandBus,
    )
    {
        $this->jwt = new Jwt(env('JWT_SECRET'));
    }

    /**
     * @throws NotFoundException
     * @throws UndefinedClassException
     * @throws UnauthorizedException|JsonException
     */
    public function auth(AuthRequest $request): JsonResponse
    {
        $user = new User()->where('email', '=', $request->input('email'))->first();
        if (!$user) {
            throw new NotFoundException('User not found');
        }

        if (!$user->authenticate($request->input('password'))) {
            throw new UnauthorizedException('Invalid credentials');
        }

        return new JsonResponse(
            ['data' => $user->toArray()],
            200,
            ['Authorization' => $this->jwt->encode([
                'sub' => $user->id,
                'email' => $user->email,
                'permissions' => $user->permissions(),
            ])],
        );
    }

    /**
     * @throws DatabaseException
     * @throws InternalServiceException
     */
    public function create(CreateUserRequest $request): JsonResponse
    {
        $data = $request->validated();
        unset($data['passwordConfirmation']);

        try {
            $user = $this->commandBus->dispatch(
                new CreateUserCommand(...$data)
            );
        } catch (PDOException $e) {
            throw new DatabaseException($e->getMessage(), $e->getCode(), $e);
        } catch (Exception $e) {
            throw new InternalServiceException($e->getMessage());
        }

        return new JsonResponse([
            'data' => $user,
        ]);
    }

    public function list(Request $request): JsonResponse
    {
        $limit = (int) $request->input('limit', 10);
        $offset = (int) $request->input('offset', 0);
        $res = new DB(table: 'users', attributes: ["COUNT(*) AS total"])->get(excludeModelMapping: true);
        $total = (int) $res[0]['total'];

        $users = User::get($limit, $offset);


        return new JsonResponse([
            'meta' => [
                'total' => $total,
                'limit' => $limit,
                'offset' => $offset,
                'count' => count($users),
                'has_more' => ($offset + $limit) < $total,
            ],
            'data' => $users,
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        return new JsonResponse([
            'data' => $request->getUser(),
        ]);
    }
}