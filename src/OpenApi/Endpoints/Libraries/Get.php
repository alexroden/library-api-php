<?php

namespace AlexRoden\LibraryApiPhp\OpenApi\Endpoints\Libraries;

use OpenApi\Attributes as OA;

#[OA\Get(
    path: "/api/libraries/{library}",
    description: "Returns a given library.",
    summary: "Get a given library",
    security: [
        ["bearerAuth" => []]
    ],
    tags: ["Libraries"],
    responses: [
        new OA\Response(
            response: 200,
            description: "Library returned",
            content: new OA\JsonContent(
                ref: "#/components/schemas/LibraryResponse"
            )
        ),
        new OA\Response(ref: "#/components/responses/Unauthorized", response: 401)
    ]
)]
class Get
{
}