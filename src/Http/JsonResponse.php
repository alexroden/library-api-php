<?php

namespace AlexRoden\LibraryApiPhp\Http;

class JsonResponse
{
    /**
     * @param array $data
     * @param int $status
     */
    public function __construct(
        public array $data,
        public int $status = 200,
        public array $headers = [],
    ) {
    }

    public function send(): void
    {
        http_response_code($this->status);

        header('Content-Type: application/json');
        foreach($this->headers as $header => $value) {
            header(sprintf('%s: %s', $header, $value));
        }

        echo json_encode($this->data);
    }
}