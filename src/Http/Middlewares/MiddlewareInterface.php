<?php

namespace AlexRoden\LibraryApiPhp\Http\Middlewares;

use AlexRoden\LibraryApiPhp\Http\Foundation\Request;

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