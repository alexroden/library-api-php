<?php

namespace AlexRoden\LibraryApiPhp\OpenApi\Endpoints\Users;

use OpenApi\Attributes as OA;

#[OA\Put(
    path: "/api/users/{id}",
    description: "Update a given user",
    summary: "Update user",
    security: [
        ["bearerAuth" => []]
    ],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            ref: "#/components/schemas/UpdateUserRequest"
        )
    ),
    tags: ["Users"],
    responses: [
        new OA\Response(
            response: 200,
            description: "User created",
            content: new OA\JsonContent(
                ref: "#/components/schemas/UserResponse"
            )
        ),
        new OA\Response(response: 401, ref: "#/components/responses/Unauthorized"),
        new OA\Response(response: 422, ref: "#/components/responses/Validation"),
    ]
)]
class Update
{
}