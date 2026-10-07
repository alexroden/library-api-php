<?php

namespace AlexRoden\LibraryApiPhp\Http\Requests;

class CreateBookRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => 'required|min:3',
            'description' => 'min:3|nullable',
            'tags' => 'array|nullable',
            'authors' => 'array|nullable',
            'published_at' => 'date|nullable',
        ];
    }
}
