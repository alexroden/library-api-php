<?php

namespace AlexRoden\LibraryApiPhp\Http\Requests;

class CouncilRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|min:3',
        ];
    }
}