<?php

namespace AlexRoden\LibraryApiPhp\OpenApi\Endpoints\Users;

use OpenApi\Attributes as OA;

#[OA\Get(
    path: "/api/user/{user}",
    description: "Returns a given user.",
    summary: "Get a given user",
    security: [
        ["bearerAuth" => []]
    ],
    tags: ["Users"],
    responses: [
        new OA\Response(
            response: 200,
            description: "Authenticated user returned",
            content: new OA\JsonContent(
                ref: "#/components/schemas/UserResponse"
            )
        ),
        new OA\Response(ref: "#/components/responses/Unauthorized", response: 401)
    ]
)]
class Get
{

}