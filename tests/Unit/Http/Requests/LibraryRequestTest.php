<?php

namespace AlexRoden\LibraryApiPhp\Tests\Unit\Http\Requests;

use AlexRoden\LibraryApiPhp\Http\Exceptions\ValidationException;
use AlexRoden\LibraryApiPhp\Http\Requests\CouncilRequest;
use AlexRoden\LibraryApiPhp\Http\Requests\LibraryRequest;
use AlexRoden\LibraryApiPhp\Tests\AbstractTestCase;

class LibraryRequestTest extends AbstractTestCase
{
    public function testRulesPassed(): void
    {
        $body = [
            'name' => $this->faker->city,
        ];

        $req = new LibraryRequest(body: $body);

        $valid = $req->validated();
        $this->assertEquals($body, [
            'name' => $valid['name'],
        ]);
    }

    public function testNameIsRequired(): void
    {
        $body = [];

        $this->expectException(ValidationException::class);

        new LibraryRequest(body: $body);
    }
}