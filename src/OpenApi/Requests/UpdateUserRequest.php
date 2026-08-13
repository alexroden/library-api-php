<?php

namespace AlexRoden\LibraryApiPhp\OpenApi\Requests;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "UpdateUserRequest",
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
            example: "password123",
            minLength: 6,
        ),
        new OA\Property(
            property: "password_confirmation",
            type: "string",
            format: "password",
            example: "password123",
        ),
        new OA\Property(
            property: "first_name",
            type: "string",
            example: "Alex",
            minLength: 3,
        ),
        new OA\Property(
            property: "last_name",
            type: "string",
            example: "Roden",
            minLength: 3,
        ),
        new OA\Property(
            property: "roles",
            type: "array",
            items: new OA\Items(
                type: "string",
            )
        ),
    ]
)]
class UpdateUserRequest
{

}
