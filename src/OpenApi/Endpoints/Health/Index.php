<?php

namespace AlexRoden\LibraryApiPhp\OpenApi\Endpoints\Health;

use OpenApi\Attributes as OA;

#[OA\Get(
    path: "/api/_health",
    summary: "API health check",
    tags: ["HealthCheck"],
    responses: [
        new OA\Response(
            response: 200,
            description: "OK response",
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: "status",
                        type: "string",
                    )
                ],
                type: "object"
            )
        ),
    ]
)]
class Index
{

}