<?php

namespace AlexRoden\LibraryApiPhp\Http\Helpers;

class JsonResponse
{
    /**
     * @param array|null $data
     * @param int $status
     * @param array $headers
     */
    public function __construct(
        public ?array $data,
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

    public function status(): int
    {
        return $this->status;
    }

    public function headers(): array
    {
        return $this->headers;
    }

    public function json(): array
    {
        return $this->data;
    }
}