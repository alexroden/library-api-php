<?php

namespace AlexRoden\LibraryApiPhp\OpenApi\Endpoints\Books;

use OpenApi\Attributes as OA;

#[OA\Put(
    path: "/api/books/{book}",
    description: "Update a given book.",
    summary: "Update book",
    security: [
        ["bearerAuth" => []]
    ],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            ref: "#/components/schemas/UpdateBookRequest"
        )
    ),
    tags: ["Books"],
    responses: [
        new OA\Response(
            response: 200,
            description: "Book returned",
            content: new OA\JsonContent(
                ref: "#/components/schemas/BookResponse"
            )
        ),
        new OA\Response(ref: "#/components/responses/Unauthorized", response: 401),
        new OA\Response(ref: "#/components/responses/Validation", response: 422),
    ]
)]
class Update
{
}
