<?php

namespace AlexRoden\LibraryApiPhp\OpenApi\Endpoints\Categories;

use OpenApi\Attributes as OA;

#[OA\Put(
    path: "/api/categories/{category}",
    description: "Update a given category.",
    summary: "Update category",
    security: [
        ["bearerAuth" => []]
    ],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            ref: "#/components/schemas/CategoryRequest"
        )
    ),
    tags: ["Categories"],
    responses: [
        new OA\Response(
            response: 200,
            description: "Category returned",
            content: new OA\JsonContent(
                ref: "#/components/schemas/CategoryResponse"
            )
        ),
        new OA\Response(ref: "#/components/responses/Unauthorized", response: 401),
        new OA\Response(ref: "#/components/responses/Validation", response: 422),
    ]
)]
class Update
{
}
