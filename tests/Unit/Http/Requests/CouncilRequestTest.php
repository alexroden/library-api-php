<?php

namespace AlexRoden\LibraryApiPhp\Tests\Unit\Http\Requests;

use AlexRoden\LibraryApiPhp\Http\Exceptions\ValidationException;
use AlexRoden\LibraryApiPhp\Http\Requests\CouncilRequest;
use AlexRoden\LibraryApiPhp\Tests\AbstractTestCase;

class CouncilRequestTest extends AbstractTestCase
{
    public function testRulesPassed(): void
    {
        $body = [
            'name' => $this->faker->city,
        ];

        $req = new CouncilRequest(body: $body);

        $valid = $req->validated();
        $this->assertEquals($body, [
            'name' => $valid['name'],
        ]);
    }

    public function testNameIsRequired(): void
    {
        $body = [];

        $this->expectException(ValidationException::class);

        new CouncilRequest(body: $body);
    }
}
