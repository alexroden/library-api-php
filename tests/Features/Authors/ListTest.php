<?php

namespace AlexRoden\LibraryApiPhp\Tests\Features\Authors;

use AlexRoden\LibraryApiPhp\Http\Foundation\Request;
use AlexRoden\LibraryApiPhp\Models\Author;
use AlexRoden\LibraryApiPhp\Tests\Factories\AuthorFactory;
use AlexRoden\LibraryApiPhp\Tests\Features\AbstractFeaturesTestCase;

class ListTest extends AbstractFeaturesTestCase
{
    private Author $author;

    protected function setUp(): void
    {
        parent::setUp();

        $this->author = AuthorFactory::create();
    }

    public function testListAuthors(): void
    {
        $this->asAuthorizedUser();

        $response = $this->handle(
            Request::create(
                method: 'GET',
                uri: '/api/authors',
            )
        );

        $this->assertEquals(200, $response->status());
        $this->assertSame(
            $this->author->toArray(),
            $response->json()['data'][0]->toArray()
        );
    }
}