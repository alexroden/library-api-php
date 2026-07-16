<?php

namespace AlexRoden\LibraryApiPhp\Http\Requests;


use AlexRoden\LibraryApiPhp\Http\Exceptions\UnauthorizedException;
use AlexRoden\LibraryApiPhp\Http\Exceptions\ValidationException;
use AlexRoden\LibraryApiPhp\Http\Foundation\Request;
use AlexRoden\LibraryApiPhp\Http\Middlewares\Validator\Validator;

abstract class FormRequest extends Request
{
    public function __construct()
    {
        parent::__construct();

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
        return $this->all();
    }
}