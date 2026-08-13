<?php

namespace AlexRoden\LibraryApiPhp\OpenApi\Endpoints\Categories;

use OpenApi\Attributes as OA;

#[OA\Get(
    path: "/api/categories",
    summary: "List categories",
    security: [
        ["bearerAuth" => []]
    ],
    tags: ["Categories"],
    parameters: [
        new OA\Parameter(ref: "#/components/parameters/Limit"),
        new OA\Parameter(ref: "#/components/parameters/Offset"),
    ],
    responses: [
        new OA\Response(
            response: 200,
            description: "Categories returned",
            content: new OA\JsonContent(
                ref: "#/components/schemas/CategoryCollection"
            )
        ),
        new OA\Response(ref: "#/components/responses/Unauthorized", response: 401)
    ]
)]
class ListCategories
{
}
