<?php

namespace AlexRoden\LibraryApiPhp\OpenApi\Endpoints\Categories;

use OpenApi\Attributes as OA;

#[OA\Post(
    path: "/api/categories",
    description: "Creates a new category.",
    summary: "Create category",
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
            response: 201,
            description: "Category created",
            content: new OA\JsonContent(
                ref: "#/components/schemas/CategoryResponse"
            )
        ),
        new OA\Response(ref: "#/components/responses/Unauthorized", response: 401),
        new OA\Response(ref: "#/components/responses/Validation", response: 422),
    ]
)]
class Create
{
}