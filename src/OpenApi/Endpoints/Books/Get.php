<?php

namespace AlexRoden\LibraryApiPhp\OpenApi\Endpoints\Books;

use OpenApi\Attributes as OA;

#[OA\Get(
    path: "/api/books/{book}",
    description: "Returns a given book.",
    summary: "Get a given book",
    security: [
        ["bearerAuth" => []]
    ],
    tags: ["Books"],
    responses: [
        new OA\Response(
            response: 200,
            description: "Book returned",
            content: new OA\JsonContent(
                ref: "#/components/schemas/BookResponse"
            )
        ),
        new OA\Response(ref: "#/components/responses/Unauthorized", response: 401)
    ]
)]
class Get
{
}
