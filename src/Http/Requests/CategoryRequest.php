<?php

namespace AlexRoden\LibraryApiPhp\Http\Requests;

class CategoryRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|min:3',
        ];
    }
}
