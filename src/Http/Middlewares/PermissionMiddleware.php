<?php

namespace AlexRoden\LibraryApiPhp\Http\Middlewares;

use AlexRoden\LibraryApiPhp\Authentication\Jwt;
use AlexRoden\LibraryApiPhp\Http\Exceptions\JwtException;
use AlexRoden\LibraryApiPhp\Http\Exceptions\PermissionException;
use AlexRoden\LibraryApiPhp\Http\Foundation\Request;

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

        $missing = array_diff($parameters, $payload['permissions']);
        if (! empty($missing)) {
            throw new PermissionException();
        }

        return $next($request);
    }
}