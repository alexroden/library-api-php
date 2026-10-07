<?php

namespace AlexRoden\LibraryApiPhp\OpenApi\Endpoints\Categories;

use OpenApi\Attributes as OA;

#[OA\Delete(
    path: "/api/categories/{category}",
    description: "Delete a given category.",
    summary: "Delete a given category",
    security: [
        ["bearerAuth" => []]
    ],
    tags: ["Categories"],
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
