<?php

namespace AlexRoden\LibraryApiPhp\Http\Controllers;

use AlexRoden\LibraryApiPhp\Authentication\Jwt;
use AlexRoden\LibraryApiPhp\Exceptions\NotFoundException;
use AlexRoden\LibraryApiPhp\Exceptions\UnauthorizedException;
use AlexRoden\LibraryApiPhp\Exceptions\UndefinedClassException;
use AlexRoden\LibraryApiPhp\Http\JsonResponse;
use AlexRoden\LibraryApiPhp\Http\Request;
use AlexRoden\LibraryApiPhp\Http\Requests\AuthRequest;
use AlexRoden\LibraryApiPhp\Models\User;

class UserController
{
    protected Jwt $jwt;

    public function __construct()
    {
        $this->jwt = new Jwt(env('JWT_SECRET'));
    }

    /**
     * @throws NotFoundException
     * @throws UndefinedClassException
     * @throws UnauthorizedException
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

    public function create(Request $request): JsonResponse
    {
        dd($request);
    }

    public function getAuthenticatedUser(Request $request): JsonResponse
    {
        return new JsonResponse([
            'data' => $request->getUser(),
        ]);
    }
}