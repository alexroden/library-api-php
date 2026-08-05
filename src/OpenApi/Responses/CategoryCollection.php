<?php

namespace AlexRoden\LibraryApiPhp\OpenApi\Responses;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "CategoryCollection",
    properties: [
        new OA\Property(
            property: "meta",
            ref: "#/components/schemas/Pagination"
        ),
        new OA\Property(
            property: "data",
            type: "array",
            items: new OA\Items(
                ref: "#/components/schemas/Category"
            )
        )
    ]
)]
class CategoryCollection
{
}