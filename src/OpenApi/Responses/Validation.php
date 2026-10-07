<?php

namespace AlexRoden\LibraryApiPhp\OpenApi\Responses;

use OpenApi\Attributes as OA;

#[OA\Response(
    response: "Validation",
    description: "Validation failed",
    content: new OA\JsonContent(
        type: "object",
        properties: [
            new OA\Property(
                property: "message",
                type: "string",
                example: "The given data was invalid."
            ),
            new OA\Property(
                property: "errors",
                type: "object",
                additionalProperties: new OA\AdditionalProperties(
                    type: "array",
                    items: new OA\Items(
                        type: "string"
                    )
                ),
                example: [
                    "password" => [
                        "password is required."
                    ],
                    "firstName" => [
                        "firstName is required."
                    ],
                    "lastName" => [
                        "lastName is required."
                    ]
                ]
            )
        ]
    )
)]
class Validation
{
}
