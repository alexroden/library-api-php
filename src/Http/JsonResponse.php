<?php

namespace App\Http;

class JsonResponse
{
    /**
     * @param array $data
     * @param int $status
     */
    public function __construct(
        public array $data,
        public int $status = 200
    ) {
    }

    public function send(): void
    {
        http_response_code($this->status);

        header('Content-Type: application/json');

        echo json_encode($this->data);
    }
}