<?php

namespace AlexRoden\LibraryApiPhp\Http\Requests;

class CreateUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
            'first_name' => 'required|min:3',
            'last_name' => 'required|min:3',
            'roles' => 'array|nullable',
        ];
    }
}
