<?php

namespace App\Controllers;

use App\Http\JsonResponse;
use App\Http\Response;

class HealthController
{
    public function index(): JsonResponse
    {
        return Response::json([
            'status' => 'ok',
        ]);
    }
}