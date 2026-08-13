<?php

namespace AlexRoden\LibraryApiPhp\Http\Requests;

class UpdateAuthorRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'first_name' => 'min:3|nullable',
            'last_name' => 'min:3|nullable',
        ];
    }
}
