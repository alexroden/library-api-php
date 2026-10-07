<?php

namespace AlexRoden\LibraryApiPhp\OpenApi\Requests;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "UpdateStockRequest",
    required: [
        "quantity",
    ],
    properties: [
        new OA\Property(
            property: "quantity",
            description: "Whole number of copies held, 0 or more. library_id and book_id are ignored if sent.",
            type: "integer",
            minimum: 0,
            example: 12,
        ),
    ]
)]
class UpdateStockRequest
{
}
