<?php

namespace AlexRoden\LibraryApiPhp\OpenApi\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "Author",
    properties: [
        new OA\Property(property: "id", type: "integer"),
        new OA\Property(property: "first_name", type: "string"),
        new OA\Property(property: "last_name", type: "string"),
        new OA\Property(property: "created_at", type: "string"),
        new OA\Property(property: "updated_at", type: "string"),
    ]
)]
class Author
{
}