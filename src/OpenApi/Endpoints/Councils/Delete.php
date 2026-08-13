<?php

namespace AlexRoden\LibraryApiPhp\OpenApi\Endpoints\Councils;

use OpenApi\Attributes as OA;

#[OA\Delete(
    path: "/api/councils/{council}",
    description: "Delete a given council.",
    summary: "Delete a given council",
    security: [
        ["bearerAuth" => []]
    ],
    tags: ["Councils"],
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
