<?php

namespace AlexRoden\LibraryApiPhp\OpenApi\Endpoints\Users;

use OpenApi\Attributes as OA;

#[OA\Post(
    path: "/api/users",
    summary: "Create user",
    description: "Creates a new user account.",
    tags: ["Users"],
    security: [
        ["bearerAuth" => []]
    ],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            ref: "#/components/schemas/CreateUserRequest"
        )
    ),
    responses: [
        new OA\Response(
            response: 201,
            description: "User created",
            content: new OA\JsonContent(
                ref: "#/components/schemas/UserResponse"
            )
        ),
        new OA\Response(response: 401, ref: "#/components/responses/Unauthorized"),
        new OA\Response(response: 422, ref: "#/components/responses/Validation"),
    ]
)]
class Create
{
}