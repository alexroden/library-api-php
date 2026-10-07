<?php

namespace AlexRoden\LibraryApiPhp\OpenApi\Endpoints\Users;

use OpenApi\Attributes as OA;

#[OA\Post(
    path: "/api/auth",
    description: "Authenticates a user and returns a JWT token.",
    summary: "Authenticate user",
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            ref: "#/components/schemas/AuthBody"
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
        new OA\Response(response: 401, ref: "#/components/responses/Unauthorized"),
        new OA\Response(response: 422, ref: "#/components/responses/Validation"),
    ]
)]
class Auth
{
}
