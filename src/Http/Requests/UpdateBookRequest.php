<?php

namespace AlexRoden\LibraryApiPhp\Http\Requests;

class UpdateBookRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => 'min:3|nullable',
            'description' => 'min:3|nullable',
            'tags' => 'array|nullable',
            'authors' => 'array|nullable',
            'published_at' => 'date|nullable',
        ];
    }
}