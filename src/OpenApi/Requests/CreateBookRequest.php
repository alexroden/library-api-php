<?php

namespace AlexRoden\LibraryApiPhp\OpenApi\Requests;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "CreateBookRequest",
    required: [
        "title",
        "description",
    ],
    properties: [
        new OA\Property(
            property: "title",
            type: "string",
            example: "Pride and Prejudice",
            minLength: 3,
        ),
        new OA\Property(
            property: "description",
            type: "string",
            example: "In early nineteenth-century England, a spirited young woman copes with the suit of a snobbish gentleman, as well as the romantic entanglements of her four sisters",
            minLength: 3,
        ),
        new OA\Property(
            property: "tags",
            type: "array",
            items: new OA\Items(
                type: "string",
            )
        ),
        new OA\Property(
            property: "authors",
            type: "array",
            items: new OA\Items(
                type: "integer",
            )
        ),
        new OA\Property(
            property: "published_at",
            type: "string",
            format: "date",
            example: "1813-01-28",
            nullable: true,
        ),
    ]
)]
class CreateBookRequest
{

}
