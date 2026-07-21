<?php

namespace AlexRoden\LibraryApiPhp\OpenApi\Endpoints\Users;

use OpenApi\Attributes as OA;

#[OA\Post(
    path: "/api/auth",
    summary: "Authenticate user",
    description: "Authenticates a user and returns a JWT token.",
    tags: ["Users"],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            ref: "#/components/schemas/AuthBody"
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
class Auth
{
}