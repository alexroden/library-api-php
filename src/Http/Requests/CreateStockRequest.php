<?php

namespace AlexRoden\LibraryApiPhp\Http\Requests;

class CreateStockRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'library_id' => 'required',
            'book_id' => 'required',
            /*
             * Not `required`: the validator treats it with empty(), which would
             * reject a legitimate opening quantity of 0. The command defaults it.
             */
            'quantity' => 'nullable',
        ];
    }

    /**
     * An explicit null quantity means the same as omitting it, so drop the key
     * and let the command's default of 0 stand rather than spreading a null
     * into an int parameter.
     */
    public function validated(): array
    {
        $validated = parent::validated();

        if (array_key_exists('quantity', $validated) && $validated['quantity'] === null) {
            unset($validated['quantity']);
        }

        return $validated;
    }
}
