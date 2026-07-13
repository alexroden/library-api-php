<?php

namespace App\Http;

class Response
{
    public static function json(array $data, int $status = 200): JsonResponse
    {
        return new JsonResponse($data, $status);
    }
}