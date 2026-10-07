<?php

namespace AlexRoden\LibraryApiPhp\OpenApi\Responses;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "LibraryResponse",
    properties: [
        new OA\Property(
            property: "data",
            ref: "#/components/schemas/Library"
        )
    ]
)]
class LibraryResponse
{
}
