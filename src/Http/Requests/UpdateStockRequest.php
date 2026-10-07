<?php

namespace AlexRoden\LibraryApiPhp\Http\Requests;

class UpdateStockRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'quantity' => 'present|non_negative_integer',
        ];
    }

    /**
     * Only the quantity is updatable; library_id and book_id are dropped so the
     * pair a stock record represents can never change.
     */
    public function validated(): array
    {
        return [
            'quantity' => (int) $this->input('quantity'),
        ];
    }
}
