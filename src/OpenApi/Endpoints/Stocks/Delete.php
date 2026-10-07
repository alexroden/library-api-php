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
        new OA\Response(ref: "#/components/responses/Unauthorized", response: 401),
        new OA\Response(response: 403, description: "Missing the stocks.delete permission"),
        new OA\Response(response: 404, description: "Stock not found"),
    ]
)]
class Delete
{
}
