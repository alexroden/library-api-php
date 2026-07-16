<?php

namespace AlexRoden\LibraryApiPhp\Controllers;

use AlexRoden\LibraryApiPhp\Http\JsonResponse;
use AlexRoden\LibraryApiPhp\Http\Response;

class HealthController
{
    public function index(): JsonResponse
    {
        return Response::json([
            'status' => 'ok',
        ]);
    }
}