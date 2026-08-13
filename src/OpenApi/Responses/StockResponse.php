<?php

namespace AlexRoden\LibraryApiPhp\OpenApi\Responses;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "StockResponse",
    properties: [
        new OA\Property(
            property: "data",
            ref: "#/components/schemas/Stock"
        )
    ]
)]
class StockResponse
{
}
