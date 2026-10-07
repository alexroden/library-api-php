<?php

namespace AlexRoden\LibraryApiPhp\Http\Requests;

use AlexRoden\LibraryApiPhp\Exceptions\UndefinedClassException;
use AlexRoden\LibraryApiPhp\Http\Exceptions\ValidationException;
use AlexRoden\LibraryApiPhp\Models\Book;
use AlexRoden\LibraryApiPhp\Models\Library;

class CreateStockRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'library_id' => 'required|non_negative_integer',
            'book_id' => 'required|non_negative_integer',
            /*
             * Not `required`: the validator treats it with empty(), which would
             * reject a legitimate opening quantity of 0. The command defaults it.
             */
            'quantity' => 'nullable|non_negative_integer',
        ];
    }

    /**
     * The validator has no access to the database, so once the ids are known to
     * be well-formed the referenced library and book are looked up here.
     *
     * @throws UndefinedClassException
     * @throws ValidationException
     */
    protected function validateResolved(): void
    {
        parent::validateResolved();

        $errors = [];

        if (Library::find((int) $this->input('library_id')) === null) {
            $errors['library_id'][] = 'The selected library does not exist.';
        }

        if (Book::find((int) $this->input('book_id')) === null) {
            $errors['book_id'][] = 'The selected book does not exist.';
        }

        if ($errors !== []) {
            throw new ValidationException($errors);
        }
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
