<?php

namespace AlexRoden\LibraryApiPhp\Http\Requests;

class CreateUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
            'firstName' => 'required|min:3',
            'lastName' => 'required|min:3',
            'roles' => 'array|nullable',
        ];
    }
}