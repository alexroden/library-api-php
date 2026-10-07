<?php

namespace AlexRoden\LibraryApiPhp\Http\Requests;

class CreateAuthorRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'first_name' => 'required|min:3',
            'last_name' => 'required|min:3',
        ];
    }
}
