<?php

namespace AlexRoden\LibraryApiPhp\OpenApi\Endpoints\Books;

use OpenApi\Attributes as OA;

#[OA\Post(
    path: "/api/books",
    description: "Creates a new book.",
    summary: "Create book",
    security: [
        ["bearerAuth" => []]
    ],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            ref: "#/components/schemas/CreateBookRequest"
        )
    ),
    tags: ["Books"],
    responses: [
        new OA\Response(
            response: 201,
            description: "Book created",
            content: new OA\JsonContent(
                ref: "#/components/schemas/BookResponse"
            )
        ),
        new OA\Response(ref: "#/components/responses/Unauthorized", response: 401),
        new OA\Response(ref: "#/components/responses/Validation", response: 422),
    ]
)]
class Create
{
}
