<?php

namespace AlexRoden\LibraryApiPhp\Http\Requests;

class UpdateUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => 'email|nullable',
            'password' => 'min:6|confirmed||nullable',
            'first_name' => 'min:3|nullable',
            'last_name' => 'min:3|nullable',
            'roles' => 'array|nullable',
        ];
    }
}