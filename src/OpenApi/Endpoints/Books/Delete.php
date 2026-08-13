<?php

namespace AlexRoden\LibraryApiPhp\OpenApi\Endpoints\Books;

use OpenApi\Attributes as OA;

#[OA\Delete(
    path: "/api/books/{book}",
    description: "Delete a given book.",
    summary: "Delete a given book",
    security: [
        ["bearerAuth" => []]
    ],
    tags: ["Books"],
    responses: [
        new OA\Response(
            response: 204,
            description: "Empty response",
        ),
        new OA\Response(ref: "#/components/responses/Unauthorized", response: 401)
    ]
)]
class Delete
{
}
