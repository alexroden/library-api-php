<?php

namespace AlexRoden\LibraryApiPhp\Tests\Features\Authors;

use AlexRoden\LibraryApiPhp\Http\Foundation\Request;
use AlexRoden\LibraryApiPhp\Models\Author;
use AlexRoden\LibraryApiPhp\Tests\Features\AbstractFeaturesTestCase;

class CreateTest extends AbstractFeaturesTestCase
{
    public function testCreateAuthor(): void
    {
        $this->asAuthorizedUser();

        $firstName = $this->faker->firstName;
        $lastName = $this->faker->lastName;

        $response = $this->handle(
            Request::create(
                method: 'POST',
                uri: '/api/authors',
                body: [
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                ]
            )
        );

        $this->assertEquals(201, $response->status());

        $author = Author::where('first_name', '=', $firstName)->where('last_name', '=', $lastName)->first();
        $this->assertNotNull($author);
        $this->assertEquals($firstName, $author->first_name);
        $this->assertEquals($lastName, $author->last_name);
    }
}