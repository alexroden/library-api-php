<?php

namespace AlexRoden\LibraryApiPhp\OpenApi\Endpoints\Authors;

use OpenApi\Attributes as OA;

#[OA\Delete(
    path: "/api/authors/{author}",
    description: "Delete a given author.",
    summary: "Delete a given author",
    security: [
        ["bearerAuth" => []]
    ],
    tags: ["Authors"],
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
