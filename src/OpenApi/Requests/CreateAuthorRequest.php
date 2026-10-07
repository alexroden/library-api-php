<?php

namespace AlexRoden\LibraryApiPhp\OpenApi\Requests;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "CreateAuthorRequest",
    required: [
        "first_name",
        "last_name",
    ],
    properties: [
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
    ]
)]
class CreateAuthorRequest
{

}
