<?php

namespace AlexRoden\LibraryApiPhp\OpenApi\Responses;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "CategoryResponse",
    properties: [
        new OA\Property(
            property: "data",
            ref: "#/components/schemas/Category"
        )
    ]
)]
class CategoryResponse
{
}
