<?php

namespace AlexRoden\LibraryApiPhp\Tests\Features\Authors;

use AlexRoden\LibraryApiPhp\Http\Foundation\Request;
use AlexRoden\LibraryApiPhp\Models\Author;
use AlexRoden\LibraryApiPhp\Tests\Factories\AuthorFactory;
use AlexRoden\LibraryApiPhp\Tests\Features\AbstractFeaturesTestCase;

class UpdateTest extends AbstractFeaturesTestCase
{
    private Author $author;

    protected function setUp(): void
    {
        parent::setUp();

        $this->author = AuthorFactory::create();
    }

    public function testUpdateAuthor(): void
    {
        $this->asAuthorizedUser();

        $firstName = 'Edgar';
        $lastName = 'Allan Poe';

        $response = $this->handle(
            Request::create(
                method: 'PUT',
                uri: "/api/authors/{$this->author->id}",
                body: [
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                ]
            )
        );

        $this->assertEquals(200, $response->status());

        $author = Author::where('first_name', '=', $firstName)->where('last_name', '=', $lastName)->first();
        $this->assertNotNull($author);
        $this->assertEquals($firstName, $author->first_name);
        $this->assertEquals($lastName, $author->last_name);
    }
}
