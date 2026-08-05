<?php

namespace AlexRoden\LibraryApiPhp\Tests\Unit\Http\Requests;

use AlexRoden\LibraryApiPhp\Http\Exceptions\ValidationException;
use AlexRoden\LibraryApiPhp\Http\Requests\CategoryRequest;
use AlexRoden\LibraryApiPhp\Tests\AbstractTestCase;

class CategoryRequestTest extends AbstractTestCase
{
    public function testRulesPassed(): void
    {
        $body = [
            'name' => $this->faker->city,
        ];

        $req = new CategoryRequest(body: $body);

        $valid = $req->validated();
        $this->assertEquals($body, [
            'name' => $valid['name'],
        ]);
    }

    public function testNameIsRequired(): void
    {
        $body = [];

        $this->expectException(ValidationException::class);

        new CategoryRequest(body: $body);
    }
}