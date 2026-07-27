<?php

namespace AlexRoden\LibraryApiPhp\Tests\Features\Authors;

use AlexRoden\LibraryApiPhp\Http\Foundation\Request;
use AlexRoden\LibraryApiPhp\Models\Author;
use AlexRoden\LibraryApiPhp\Tests\Factories\AuthorFactory;
use AlexRoden\LibraryApiPhp\Tests\Features\AbstractFeaturesTestCase;

class DeleteTest extends AbstractFeaturesTestCase
{
    private Author $author;

    protected function setUp(): void
    {
        parent::setUp();

        $this->author = AuthorFactory::create();
    }

    public function testDeleteLibrary(): void
    {
        $this->asAuthorizedUser();

        $response = $this->handle(
            Request::create(
                method: 'DELETE',
                uri: "/api/authors/{$this->author->id}",
            )
        );

        $this->assertEquals(204, $response->status());

        $author = Author::where('id', '=', $this->author->id)->first();
        $this->assertNull($author);
    }
}