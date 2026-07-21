<?php

namespace AlexRoden\LibraryApiPhp\OpenApi\Endpoints\Users;

use OpenApi\Attributes as OA;

#[OA\Get(
    path: "/api/me",
    summary: "Get current user",
    description: "Returns the current user.",
    tags: ["Users"],
    security: [
        ["bearerAuth" => []]
    ],
    responses: [
        new OA\Response(
            response: 200,
            description: "Authenticated user returned",
            content: new OA\JsonContent(
                ref: "#/components/schemas/UserResponse"
            )
        ),
        new OA\Response(response: 401, ref: "#/components/responses/Unauthorized")
    ]
)]
class Me
{

}