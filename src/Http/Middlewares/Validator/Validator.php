<?php

namespace AlexRoden\LibraryApiPhp\Http\Middlewares\Validator;

use DateTimeImmutable;

class Validator
{
    private const string DATE_FORMAT = 'Y-m-d';

    private array $errors = [];

    public function __construct(
        private readonly array $data,
        private readonly array $rules
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

                if (
                    $rule === 'date'
                    && isset($this->data[$field])
                    && !$this->isDate($this->data[$field])
                ) {
                    $this->errors[$field][] = ucfirst($field).' must be a '.self::DATE_FORMAT.' date.';
                }
            }
        }
    }

    /**
     * A date is only valid if it round-trips through the expected format, which
     * rejects both a wrong shape (01/02/2020) and an impossible day (2020-02-31).
     */
    private function isDate(mixed $value): bool
    {
        if (!is_string($value)) {
            return false;
        }

        $date = DateTimeImmutable::createFromFormat(self::DATE_FORMAT, $value);

        return $date !== false && $date->format(self::DATE_FORMAT) === $value;
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
