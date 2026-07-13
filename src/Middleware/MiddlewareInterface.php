<?php

namespace App\Middleware;

use App\Http\Request;

interface MiddlewareInterface
{
    /**
     * @param Request $request
     * @param callable $next
     *
     * @return mixed
     */
    public function handle(Request $request, callable $next): mixed;
}