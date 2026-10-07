<?php

namespace AlexRoden\LibraryApiPhp\Http\Requests;


use AlexRoden\LibraryApiPhp\Http\Exceptions\UnauthorizedException;
use AlexRoden\LibraryApiPhp\Http\Exceptions\ValidationException;
use AlexRoden\LibraryApiPhp\Http\Foundation\Request;
use AlexRoden\LibraryApiPhp\Http\Middlewares\Validator\Validator;

abstract class FormRequest extends Request
{
    public function __construct(
        ?string $method = null,
        ?string $uri = null,
        ?array $query = null,
        ?array $body = null,
        ?array $headers = [],
        ?array $files = null,
    ) {
        parent::__construct(
            method: $method,
            uri: $uri,
            query: $query,
            body: $body,
            headers: $headers,
            files: $files,
        );

        $this->validateResolved();
    }

    abstract public function rules(): array;

    public function authorize(): bool
    {
        return true;
    }

    /**
     * @throws UnauthorizedException
     * @throws ValidationException
     */
    protected function validateResolved(): void
    {
        if (!$this->authorize()) {
            throw new UnauthorizedException();
        }


        $validator = new Validator(
            $this->all(),
            $this->rules()
        );

        if ($validator->fails()) {
            throw new ValidationException(
                $validator->errors()
            );
        }
    }

    public function validated(): array
    {
        return $this->camelCaseKeys($this->all());
    }

    protected function camelCaseKeys(array $array): array
    {
        $result = [];

        foreach ($array as $key => $value) {
            $newKey = is_string($key)
                ? lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $key))))
                : $key;

            $result[$newKey] = is_array($value)
                ? $this->camelCaseKeys($value)
                : $value;
        }

        return $result;
    }
}
