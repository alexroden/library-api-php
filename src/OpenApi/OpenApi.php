<?php

namespace AlexRoden\LibraryApiPhp\OpenApi;

use OpenApi\Attributes as OA;

#[OA\Info(
    title: "Library API",
    version: "1.0.0",
    description: "API documentation for the Library API"
)]
#[OA\Server(
    url: "http://localhost:8000",
    description: "Local development server"
)]
#[OA\SecurityScheme(
    securityScheme: "bearerAuth",
    type: "http",
    scheme: "bearer",
    bearerFormat: "JWT"
)]
class OpenApi
{
}
