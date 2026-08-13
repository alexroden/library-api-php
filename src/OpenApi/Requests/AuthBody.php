<?php

namespace AlexRoden\LibraryApiPhp\OpenApi\Requests;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "AuthBody",
    required: [
        "email",
        "password"
    ],
    properties: [
        new OA\Property(
            property: "email",
            type: "string",
            example: "alex@example.com"
        ),
        new OA\Property(
            property: "password",
            type: "string",
            example: "password"
        )
    ]
)]
class AuthBody
{

}
