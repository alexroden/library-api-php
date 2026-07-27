<?php

namespace AlexRoden\LibraryApiPhp\OpenApi\Endpoints\Authors;

use OpenApi\Attributes as OA;

#[OA\Put(
    path: "/api/authors/{author}",
    description: "Update a given author.",
    summary: "Update author",
    security: [
        ["bearerAuth" => []]
    ],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            ref: "#/components/schemas/AuthorRequest"
        )
    ),
    tags: ["Authors"],
    responses: [
        new OA\Response(
            response: 200,
            description: "Author returned",
            content: new OA\JsonContent(
                ref: "#/components/schemas/AuthorResponse"
            )
        ),
        new OA\Response(ref: "#/components/responses/Unauthorized", response: 401),
        new OA\Response(ref: "#/components/responses/Validation", response: 422),
    ]
)]
class Update
{
}