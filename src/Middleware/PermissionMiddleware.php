<?php

namespace AlexRoden\LibraryApiPhp\Middleware;

use AlexRoden\LibraryApiPhp\Authentication\Jwt;
use AlexRoden\LibraryApiPhp\Exceptions\JwtException;
use AlexRoden\LibraryApiPhp\Exceptions\PermissionException;
use AlexRoden\LibraryApiPhp\Http\Request;
use AlexRoden\LibraryApiPhp\Models\User;

class PermissionMiddleware implements MiddlewareInterface
{
    protected Jwt $jwt;

    public function __construct()
    {
        $this->jwt = new Jwt(env('JWT_SECRET'));
    }

    /**
     * @throws JwtException
     * @throws \JsonException
     * @throws PermissionException
     */
    public function handle(Request $request, callable $next, mixed ... $parameters): mixed
    {
        $token = $request->bearerToken();

        $payload = $this->jwt->decode($token);

        $matches = array_intersect($payload['permissions'], $parameters);
        if (!empty($matches)) {
            throw new PermissionException();
        }

        return $next($request);
    }
}