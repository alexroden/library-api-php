<?php

namespace App\Middleware;

use App\Http\Request;

class AuthMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, callable $next): mixed
    {
        var_dump("HI");
        die();
    }
}