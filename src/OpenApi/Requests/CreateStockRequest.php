<?php

namespace AlexRoden\LibraryApiPhp\OpenApi\Requests;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "CreateStockRequest",
    required: [
        "library_id",
        "book_id",
    ],
    properties: [
        new OA\Property(
            property: "library_id",
            type: "integer",
            example: 1,
        ),
        new OA\Property(
            property: "book_id",
            type: "integer",
            example: 1,
        ),
        new OA\Property(
            property: "quantity",
            type: "integer",
            example: 12,
            default: 0,
            nullable: true,
        ),
    ]
)]
class CreateStockRequest
{
}
