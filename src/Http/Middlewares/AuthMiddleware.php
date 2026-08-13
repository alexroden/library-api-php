<?php

namespace AlexRoden\LibraryApiPhp\Http\Middlewares;

use AlexRoden\LibraryApiPhp\Authentication\Jwt;
use AlexRoden\LibraryApiPhp\Exceptions\UndefinedClassException;
use AlexRoden\LibraryApiPhp\Http\Exceptions\JwtException;
use AlexRoden\LibraryApiPhp\Http\Exceptions\UnauthorizedException;
use AlexRoden\LibraryApiPhp\Http\Foundation\Request;
use AlexRoden\LibraryApiPhp\Models\User;
use JsonException;

class AuthMiddleware implements MiddlewareInterface
{
    protected Jwt $jwt;

    public function __construct()
    {
        $this->jwt = new Jwt(env('JWT_SECRET'));
    }

    /**
     * @throws JwtException
     * @throws JsonException
     * @throws UnauthorizedException
     * @throws UndefinedClassException
     */
    public function handle(Request $request, callable $next, mixed ... $parameters): mixed
    {
        $token = $request->bearerToken();
        $payload = $this->jwt->decode($token);

        $user = User::find($payload['sub']);
        if (!$user) throw new UnauthorizedException();

        $request->setUser($user);

        return $next($request);
    }
}