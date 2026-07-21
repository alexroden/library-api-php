<?php

namespace AlexRoden\LibraryApiPhp\Http\Middlewares\Validator;

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
                if (
                    $rule === 'nullable'
                    && isset($this->data[$field])
                ) {
                    continue;
                }

                if (
                    $rule === 'required'
                    && empty($this->data[$field])
                ) {
                    $this->errors[$field][] = $field.' is required.';
                }

                if (
                    $rule === 'email'
                    && isset($this->data[$field])
                    && !filter_var($this->data[$field], FILTER_VALIDATE_EMAIL)) {
                    $this->errors[$field][] = 'Invalid email.';
                }

                if (
                    (str_starts_with($rule, 'min:') || str_starts_with($rule, 'max:'))
                    && isset($this->data[$field])
                ) {
                    $r = explode(':', $rule);
                    $len = (int) $r[1];
                    if (strlen($this->data[$field]) < $len) {
                        $this->errors[$field][] = ucfirst($r[0])." should be {$len}.";
                    }
                }

                if (
                    $rule === 'confirmed'
                    && isset($this->data[$field])
                ) {
                    if (!isset($this->data[$field.'_confirmation'])) {
                        $this->errors[$field][] = 'Confirmation not set.';
                    } else if ($this->data[$field] !== $this->data[$field.'_confirmation']) {
                        $this->errors[$field][] = 'Confirmation not matched.';
                    }
                }

                if (
                    $rule === 'array'
                    && isset($this->data[$field])
                    && !is_array($this->data[$field])
                ) {
                    $this->errors[$field][] = ucfirst($field).' must be an array.';
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