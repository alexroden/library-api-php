<?php

namespace AlexRoden\LibraryApiPhp\OpenApi\Responses;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "UserResponse",
    properties: [
        new OA\Property(
            property: "data",
            ref: "#/components/schemas/User"
        )
    ]
)]
class UserResponse
{
}
