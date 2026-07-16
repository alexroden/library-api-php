<?php

namespace AlexRoden\LibraryApiPhp\Middleware;

use AlexRoden\LibraryApiPhp\Http\Request;

interface MiddlewareInterface
{
    /**
     * @param Request $request
     * @param callable $next
     *
     * @return mixed
     */
    public function handle(Request $request, callable $next, mixed ...$parameters): mixed;
}