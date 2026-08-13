<?php

namespace AlexRoden\LibraryApiPhp\Http\Middlewares;

use AlexRoden\LibraryApiPhp\Http\Foundation\Request;

interface MiddlewareInterface
{
    /**
     * @param Request $request
     * @param callable $next
     * @param mixed ...$parameters
     * @return mixed
     */
    public function handle(Request $request, callable $next, mixed ...$parameters): mixed;
}
