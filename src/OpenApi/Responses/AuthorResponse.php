<?php

namespace AlexRoden\LibraryApiPhp\OpenApi\Responses;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "AuthorResponse",
    properties: [
        new OA\Property(
            property: "data",
            ref: "#/components/schemas/Author"
        )
    ]
)]
class AuthorResponse
{
}