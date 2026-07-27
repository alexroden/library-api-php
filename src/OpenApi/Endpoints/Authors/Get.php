<?php

namespace AlexRoden\LibraryApiPhp\OpenApi\Endpoints\Authors;

use OpenApi\Attributes as OA;

#[OA\Get(
    path: "/api/authors/{author}",
    description: "Returns a given author.",
    summary: "Get a given author",
    security: [
        ["bearerAuth" => []]
    ],
    tags: ["Authors"],
    responses: [
        new OA\Response(
            response: 200,
            description: "Author returned",
            content: new OA\JsonContent(
                ref: "#/components/schemas/AuthorResponse"
            )
        ),
        new OA\Response(ref: "#/components/responses/Unauthorized", response: 401)
    ]
)]
class Get
{
}