<?php

namespace AlexRoden\LibraryApiPhp\Http\Controllers;

use AlexRoden\LibraryApiPhp\Http\Foundation\Response;
use AlexRoden\LibraryApiPhp\Http\Helpers\JsonResponse;
use OpenApi\Attributes as OA;

class HealthController
{
    #[OA\Get(
        path: "/api/_health",
        summary: "API health check",
        tags: ["HealthCheck"],
        responses: [
            new OA\Response(
                response: 200,
                description: "OK response",
                content: new OA\JsonContent(
                    type: "object",
                    properties: [
                        new OA\Property(
                            property: "status",
                            type: "string",
                        )
                    ]
                )
            ),
        ]
    )]
    public function index(): JsonResponse
    {
        return Response::json([
            'status' => 'ok',
        ]);
    }
}