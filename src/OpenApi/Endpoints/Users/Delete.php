<?php

namespace AlexRoden\LibraryApiPhp\OpenApi\Endpoints\Users;

use OpenApi\Attributes as OA;

#[OA\Delete(
    path: "/api/users/{user}",
    description: "delete a given user",
    summary: "Delete user",
    security: [
        ["bearerAuth" => []]
    ],
    tags: ["Users"],
    responses: [
        new OA\Response(
            response: 204,
            description: "Empty response",
        ),
        new OA\Response(ref: "#/components/responses/Unauthorized", response: 401),
    ]
)]
class Delete
{
}
