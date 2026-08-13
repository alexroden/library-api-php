<?php

namespace AlexRoden\LibraryApiPhp\OpenApi\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "Stock",
    properties: [
        new OA\Property(property: "id", type: "integer"),
        new OA\Property(property: "library_id", type: "integer"),
        new OA\Property(property: "book_id", type: "integer"),
        new OA\Property(property: "quantity", type: "integer"),
        new OA\Property(property: "created_at", type: "string"),
        new OA\Property(property: "updated_at", type: "string"),
    ]
)]
class Stock
{
}
