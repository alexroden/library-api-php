<?php

namespace AlexRoden\LibraryApiPhp\OpenApi\Responses;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "CouncilResponse",
    properties: [
        new OA\Property(
            property: "data",
            ref: "#/components/schemas/Council"
        )
    ]
)]
class CouncilResponse
{
}