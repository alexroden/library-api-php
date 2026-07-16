<?php

namespace AlexRoden\LibraryApiPhp\Http;

readonly class Request
{
    /**
     * @param string $method
     * @param string $path
     * @param array $headers
     */
    public function __construct(
        public string $method,
        public string $path,
        public array  $headers = [],
    ) {
    }

    /**
     * @return Request
     */
    public static function capture(): self
    {
        return new self(
            $_SERVER['REQUEST_METHOD'],
            parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH),
            getallheaders() ?: [],
        );
    }
}