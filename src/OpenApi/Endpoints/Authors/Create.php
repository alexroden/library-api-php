<?php

namespace AlexRoden\LibraryApiPhp\OpenApi\Endpoints\Authors;

use OpenApi\Attributes as OA;

#[OA\Post(
    path: "/api/authors",
    description: "Creates a new author.",
    summary: "Create author",
    security: [
        ["bearerAuth" => []]
    ],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            ref: "#/components/schemas/CreateAuthorRequest"
        )
    ),
    tags: ["Authors"],
    responses: [
        new OA\Response(
            response: 201,
            description: "Author created",
            content: new OA\JsonContent(
                ref: "#/components/schemas/AuthorResponse"
            )
        ),
        new OA\Response(ref: "#/components/responses/Unauthorized", response: 401),
        new OA\Response(ref: "#/components/responses/Validation", response: 422),
    ]
)]
class Create
{
}
