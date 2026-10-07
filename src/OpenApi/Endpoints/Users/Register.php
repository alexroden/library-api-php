<?php

namespace AlexRoden\LibraryApiPhp\OpenApi\Endpoints\Users;

use OpenApi\Attributes as OA;

#[OA\Post(
    path: "/api/register",
    description: "Registers a new user account.",
    summary: "Register user",
    security: [
        ["bearerAuth" => []]
    ],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            ref: "#/components/schemas/CreateUserRequest"
        )
    ),
    tags: ["Users"],
    responses: [
        new OA\Response(
            response: 201,
            description: "User created",
            headers: [
                new OA\Header(
                    header: "Authorization",
                    description: "The authorization token.",
                    schema: new OA\Schema(
                        type: "string"
                    )
                ),
            ],
            content: new OA\JsonContent(
                ref: "#/components/schemas/UserResponse"
            )
        ),
        new OA\Response(ref: "#/components/responses/Unauthorized", response: 401),
        new OA\Response(ref: "#/components/responses/Validation", response: 422),
    ]
)]
class Register
{
}
