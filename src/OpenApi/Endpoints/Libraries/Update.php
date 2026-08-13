<?php

namespace AlexRoden\LibraryApiPhp\OpenApi\Endpoints\Libraries;

use OpenApi\Attributes as OA;

#[OA\Put(
    path: "/api/libraries/{library}",
    description: "Update a given library.",
    summary: "Update library",
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
            response: 200,
            description: "Library returned",
            content: new OA\JsonContent(
                ref: "#/components/schemas/LibraryResponse"
            )
        ),
        new OA\Response(ref: "#/components/responses/Unauthorized", response: 401),
        new OA\Response(ref: "#/components/responses/Validation", response: 422),
    ]
)]
class Update
{
}
