<?php

namespace AlexRoden\LibraryApiPhp\OpenApi\Endpoints\Books;

use OpenApi\Attributes as OA;

#[OA\Get(
    path: "/api/books",
    summary: "List books",
    security: [
        ["bearerAuth" => []]
    ],
    tags: ["Books"],
    parameters: [
        new OA\Parameter(ref: "#/components/parameters/Limit"),
        new OA\Parameter(ref: "#/components/parameters/Offset"),
    ],
    responses: [
        new OA\Response(
            response: 200,
            description: "Books returned",
            content: new OA\JsonContent(
                ref: "#/components/schemas/BookCollection"
            )
        ),
        new OA\Response(ref: "#/components/responses/Unauthorized", response: 401)
    ]
)]
class ListBooks
{
}