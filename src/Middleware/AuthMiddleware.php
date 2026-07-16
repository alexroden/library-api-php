<?php

namespace AlexRoden\LibraryApiPhp\Middleware;

use AlexRoden\LibraryApiPhp\Authentication\Jwt;
use AlexRoden\LibraryApiPhp\Exceptions\JwtException;
use AlexRoden\LibraryApiPhp\Http\Request;
use AlexRoden\LibraryApiPhp\Models\User;

class AuthMiddleware implements MiddlewareInterface
{
    protected Jwt $jwt;

    public function __construct()
    {
        $this->jwt = new Jwt(env('JWT_SECRET'));
    }

    /**
     * @throws JwtException
     * @throws \JsonException
     */
    public function handle(Request $request, callable $next): mixed
    {
        $token = $request->bearerToken();

        $payload = $this->jwt->decode($token);

        $request->setUser(User::find($payload['sub']));

        return $next($request);
    }
}