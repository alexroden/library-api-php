<?php

namespace AlexRoden\LibraryApiPhp\OpenApi\Responses;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "BookResponse",
    properties: [
        new OA\Property(
            property: "data",
            ref: "#/components/schemas/Book"
        )
    ]
)]
class BookResponse
{
}