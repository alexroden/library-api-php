<?php

namespace AlexRoden\LibraryApiPhp\OpenApi\Endpoints\Libraries;

use OpenApi\Attributes as OA;

#[OA\Delete(
    path: "/api/libraries/{library}",
    description: "Delete a given library.",
    summary: "Delete a given library",
    security: [
        ["bearerAuth" => []]
    ],
    tags: ["Libraries"],
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
