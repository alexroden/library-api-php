<?php

namespace AlexRoden\LibraryApiPhp\OpenApi\Endpoints\Categories;

use OpenApi\Attributes as OA;

#[OA\Get(
    path: "/api/categories/{category}",
    description: "Returns a given category.",
    summary: "Get a given category",
    security: [
        ["bearerAuth" => []]
    ],
    tags: ["Categories"],
    responses: [
        new OA\Response(
            response: 200,
            description: "Category returned",
            content: new OA\JsonContent(
                ref: "#/components/schemas/CategoryResponse"
            )
        ),
        new OA\Response(ref: "#/components/responses/Unauthorized", response: 401)
    ]
)]
class Get
{
}
