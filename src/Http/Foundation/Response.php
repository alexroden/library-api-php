<?php

namespace AlexRoden\LibraryApiPhp\Http\Foundation;

use AlexRoden\LibraryApiPhp\Http\Helpers\JsonResponse;

class Response
{
    /**
     * @param array $data
     * @param int $status
     *
     * @return JsonResponse
     */
    public static function json(array $data, int $status = 200): JsonResponse
    {
        return new JsonResponse($data, $status);
    }
}
