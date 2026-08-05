<?php

namespace AlexRoden\LibraryApiPhp\Tests\Unit\Http\Requests;

use AlexRoden\LibraryApiPhp\Http\Exceptions\ValidationException;
use AlexRoden\LibraryApiPhp\Http\Requests\CreateBookRequest;
use AlexRoden\LibraryApiPhp\Tests\AbstractTestCase;

class CreateBookRequestTest extends AbstractTestCase
{
    public function testRulesPassed(): void
    {
        $body = [
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'tags' => [
                $this->faker->word(),
                $this->faker->word(),
            ],
        ];

        $req = new CreateBookRequest(body: $body);

        $valid = $req->validated();
        $this->assertEquals($body, [
            'title' => $valid['title'],
            'description' => $valid['description'],
            'tags' => $valid['tags'],
        ]);
    }

    public function testTitleMustBeValid(): void
    {
        $title = 'a';

        $body = [
            'title' => $title,
            'description' => $this->faker->paragraph(),
            'tags' => [
                $this->faker->word(),
                $this->faker->word(),
            ],
        ];

        $this->expectException(ValidationException::class);

        new CreateBookRequest(body: $body);
    }
}