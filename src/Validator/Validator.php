<?php

namespace AlexRoden\LibraryApiPhp\Validator;

class Validator
{
    private array $errors = [];

    public function __construct(
        private array $data,
        private array $rules
    ) {
        $this->validate();
    }

    private function validate(): void
    {
        foreach ($this->rules as $field => $rules) {
            foreach (explode('|', $rules) as $rule) {
                if ($rule === 'required'
                    && empty($this->data[$field])) {
                    $this->errors[$field][] = 'Required.';
                }

                if ($rule === 'email'
                    && isset($this->data[$field])
                    && !filter_var($this->data[$field], FILTER_VALIDATE_EMAIL)) {
                    $this->errors[$field][] = 'Invalid email.';
                }
            }
        }
    }

    public function fails(): bool
    {
        return !empty($this->errors);
    }

    public function errors(): array
    {
        return $this->errors;
    }
}