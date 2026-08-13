<?php

namespace AlexRoden\LibraryApiPhp\Authentication;

use AlexRoden\LibraryApiPhp\Http\Exceptions\JwtException;

class Jwt
{
    public function __construct(
        private readonly string $secret,
        private readonly int $ttl = 3600,
    ) {
    }

    /**
     * @throws \JsonException
     */
    public function encode(array $payload): string
    {
        $header = [
            'typ' => 'JWT',
            'alg' => 'HS256',
        ];

        $header = $this->base64UrlEncode(
            json_encode($header, JSON_THROW_ON_ERROR)
        );

        $payload += [
            'iat' => time(),
            'exp' => time() + $this->ttl,
        ];

        $payload = $this->base64UrlEncode(
            json_encode($payload, JSON_THROW_ON_ERROR)
        );

        $signature = $this->sign(
            "{$header}.{$payload}"
        );

        return "{$header}.{$payload}.{$signature}";
    }

    /**
     * @throws JwtException
     * @throws \JsonException
     */
    public function decode(string $token): array
    {
        $parts = explode('.', $token);

        if (count($parts) !== 3) {
            throw new JwtException('Invalid JWT.');
        }

        [$header, $payload, $signature] = $parts;

        $expected = $this->sign(
            "{$header}.{$payload}"
        );

        if (! hash_equals($expected, $signature)) {
            throw new JwtException('Invalid signature.');
        }

        $payload = json_decode(
            $this->base64UrlDecode($payload),
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        if (isset($payload['exp']) && $payload['exp'] < time()) {
            throw new JwtException('Token has expired.');
        }

        if (isset($payload['nbf']) && $payload['nbf'] > time()) {
            throw new JwtException('Token is not yet valid.');
        }

        return $payload;
    }

    private function sign(string $data): string
    {
        return $this->base64UrlEncode(
            hash_hmac(
                'sha256',
                $data,
                $this->secret,
                true
            )
        );
    }

    private function base64UrlEncode(string $data): string
    {
        return $data
                |> base64_encode(...)
                |> (fn($x) => strtr($x, '+/', '-_'))
                |> (fn($x) => rtrim($x, '='));
    }

    private function base64UrlDecode(string $data): string
    {
        return base64_decode(
            strtr(
                $data,
                '-_',
                '+/'
            )
        );
    }
}
