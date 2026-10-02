<?php

namespace AlexRoden\LibraryApiPhp\OpenApi\Endpoints\Books;

use OpenApi\Attributes as OA;

#[OA\Get(
    path: "/api/books/{book}/stocks",
    description: "Returns the stock of a given book across libraries. Pass library_id to narrow the list to that library; a library holding no stock of the book returns an empty list.",
    summary: "List stock for a given book",
    security: [
        ["bearerAuth" => []]
    ],
    tags: ["Books", "Stocks"],
    parameters: [
        new OA\Parameter(
            name: "book",
            in: "path",
            required: true,
            schema: new OA\Schema(type: "integer")
        ),
        new OA\Parameter(
            name: "library_id",
            description: "Only return the stock held by this library.",
            in: "query",
            required: false,
            schema: new OA\Schema(type: "integer")
        ),
        new OA\Parameter(ref: "#/components/parameters/Limit"),
        new OA\Parameter(ref: "#/components/parameters/Offset"),
    ],
    responses: [
        new OA\Response(
            response: 200,
            description: "Stock returned",
            content: new OA\JsonContent(
                ref: "#/components/schemas/StockCollection"
            )
        ),
        new OA\Response(ref: "#/components/responses/Unauthorized", response: 401),
        new OA\Response(response: 404, description: "Book not found"),
    ]
)]
class ListBookStocks
{
}
