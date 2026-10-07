<?php

namespace AlexRoden\LibraryApiPhp\OpenApi\Endpoints\Libraries;

use OpenApi\Attributes as OA;

#[OA\Post(
    path: "/api/libraries",
    description: "Creates a new library.",
    summary: "Create library",
    security: [
        ["bearerAuth" => []]
    ],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            ref: "#/components/schemas/LibraryRequest"
        )
    ),
    tags: ["Libraries"],
    responses: [
        new OA\Response(
            response: 201,
            description: "Library created",
            content: new OA\JsonContent(
                ref: "#/components/schemas/LibraryResponse"
            )
        ),
        new OA\Response(ref: "#/components/responses/Unauthorized", response: 401),
        new OA\Response(ref: "#/components/responses/Validation", response: 422),
    ]
)]
class Create
{
}
