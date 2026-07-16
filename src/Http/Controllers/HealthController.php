<?php

namespace AlexRoden\LibraryApiPhp\Http\Controllers;

use AlexRoden\LibraryApiPhp\Http\Foundation\Response;
use AlexRoden\LibraryApiPhp\Http\Helpers\JsonResponse;

class HealthController
{
    public function index(): JsonResponse
    {
        return Response::json([
            'status' => 'ok',
        ]);
    }
}