<?php

namespace AlexRoden\LibraryApiPhp\OpenApi\Endpoints\Libraries;

use OpenApi\Attributes as OA;

#[OA\Get(
    path: "/api/libraries/{library}/stocks",
    description: "Returns the stock held by a given library. Pass book_id to narrow the list to that book; a book the library holds no stock for returns an empty list.",
    summary: "List stock for a given library",
    security: [
        ["bearerAuth" => []]
    ],
    tags: ["Libraries", "Stocks"],
    parameters: [
        new OA\Parameter(
            name: "library",
            in: "path",
            required: true,
            schema: new OA\Schema(type: "integer")
        ),
        new OA\Parameter(
            name: "book_id",
            description: "Only return the stock held for this book.",
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
        new OA\Response(response: 404, description: "Library not found"),
    ]
)]
class ListLibraryStocks
{
}
