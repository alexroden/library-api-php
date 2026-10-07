<?php

namespace AlexRoden\LibraryApiPhp\OpenApi\Endpoints\Stocks;

use OpenApi\Attributes as OA;

#[OA\Delete(
    path: "/api/stocks/{stock}",
    description: "Deletes a given stock record. The library and book it referred to are not affected.",
    summary: "Delete a given stock record",
    security: [
        ["bearerAuth" => []]
    ],
    tags: ["Stocks"],
    parameters: [
        new OA\Parameter(
            name: "stock",
            in: "path",
            required: true,
            schema: new OA\Schema(type: "integer")
        ),
    ],
    responses: [
        new OA\Response(
            response: 204,
            description: "Empty response",
        ),
        new OA\Response(
            response: 401,
            description: "Missing or invalid token, or the token lacks the stocks.delete permission",
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "message", type: "string"),
                ]
            )
        ),
        new OA\Response(response: 404, description: "Stock not found"),
    ]
)]
class Delete
{
}
