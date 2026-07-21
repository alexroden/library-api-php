<?php

namespace AlexRoden\LibraryApiPhp\OpenApi\Requests;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "CreateUserRequest",
    required: [
        "email",
        "password",
        "passwordConfirmation",
        "first_name",
        "last_name",
    ],
    properties: [
        new OA\Property(
            property: "email",
            type: "string",
            format: "email",
            example: "alex@example.com",
        ),
        new OA\Property(
            property: "password",
            type: "string",
            format: "password",
            minLength: 8,
            example: "password123",
        ),
        new OA\Property(
            property: "passwordConfirmation",
            type: "string",
            format: "password",
            example: "password123",
        ),
        new OA\Property(
            property: "first_name",
            type: "string",
            example: "Alex",
        ),
        new OA\Property(
            property: "last_name",
            type: "string",
            example: "Roden",
        ),
    ]
)]
class CreateUserRequest
{

}