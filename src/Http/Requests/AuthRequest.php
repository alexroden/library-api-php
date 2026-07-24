<?php

namespace AlexRoden\LibraryApiPhp\Http\Requests;

class AuthRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'password' => 'required',
        ];
    }
}