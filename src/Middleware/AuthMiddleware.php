<?php

namespace AlexRoden\LibraryApiPhp\Middleware;

use AlexRoden\LibraryApiPhp\Http\Request;

class AuthMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, callable $next): mixed
    {
        var_dump("HI");
        die();
    }
}