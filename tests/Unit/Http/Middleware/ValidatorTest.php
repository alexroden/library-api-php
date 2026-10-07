<?php

namespace AlexRoden\LibraryApiPhp\Tests\Unit\Http\Middleware;

use AlexRoden\LibraryApiPhp\Http\Middlewares\Validator\Validator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class ValidatorTest extends TestCase
{
    public static function validNonNegativeIntegers(): array
    {
        return [
            'zero' => [0],
            'positive int' => [12],
            'numeric string' => ['7'],
            'zero string' => ['0'],
        ];
    }

    #[DataProvider('validNonNegativeIntegers')]
    public function testNonNegativeIntegerAcceptsWholeNumbers(mixed $value): void
    {
        $validator = new Validator(['quantity' => $value], ['quantity' => 'non_negative_integer']);

        $this->assertFalse($validator->fails());
    }

    public static function invalidNonNegativeIntegers(): array
    {
        return [
            'negative' => [-1],
            'negative string' => ['-3'],
            'float' => [1.5],
            'float string' => ['1.5'],
            'word' => ['abc'],
            'empty string' => [''],
            'array' => [[1]],
            'bool' => [true],
        ];
    }

    #[DataProvider('invalidNonNegativeIntegers')]
    public function testNonNegativeIntegerRejectsOtherValues(mixed $value): void
    {
        $validator = new Validator(['quantity' => $value], ['quantity' => 'non_negative_integer']);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('quantity', $validator->errors());
    }

    public function testNonNegativeIntegerIsSkippedWhenAbsent(): void
    {
        $validator = new Validator([], ['quantity' => 'non_negative_integer']);

        $this->assertFalse($validator->fails());
    }

    public function testPresentAcceptsZero(): void
    {
        $validator = new Validator(['quantity' => 0], ['quantity' => 'present']);

        $this->assertFalse($validator->fails());
    }

    public function testPresentRejectsAMissingField(): void
    {
        $validator = new Validator([], ['quantity' => 'present']);

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('quantity', $validator->errors());
    }

    public function testPresentRejectsNull(): void
    {
        $validator = new Validator(['quantity' => null], ['quantity' => 'present']);

        $this->assertTrue($validator->fails());
    }
}
