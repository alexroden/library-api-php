<?php

namespace AlexRoden\LibraryApiPhp\OpenApi\Endpoints\Authors;

use OpenApi\Attributes as OA;

#[OA\Get(
    path: "/api/authors",
    summary: "List authors",
    security: [
        ["bearerAuth" => []]
    ],
    tags: ["Authors"],
    parameters: [
        new OA\Parameter(ref: "#/components/parameters/Limit"),
        new OA\Parameter(ref: "#/components/parameters/Offset"),
    ],
    responses: [
        new OA\Response(
            response: 200,
            description: "Authors returned",
            content: new OA\JsonContent(
                ref: "#/components/schemas/AuthorCollection"
            )
        ),
        new OA\Response(ref: "#/components/responses/Unauthorized", response: 401)
    ]
)]
class ListAuthors
{
}
