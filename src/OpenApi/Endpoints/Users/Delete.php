<?php

namespace AlexRoden\LibraryApiPhp\OpenApi\Endpoints\Users;

use OpenApi\Attributes as OA;

#[OA\Delete(
    path: "/api/users/{id}",
    description: "delete a given user",
    summary: "Delete user",
    security: [
        ["bearerAuth" => []]
    ],
    tags: ["Users"],
    responses: [
        new OA\Response(
            response: 204,
            description: "User created",
        ),
        new OA\Response(response: 401, ref: "#/components/responses/Unauthorized"),
    ]
)]
class Delete
{
}