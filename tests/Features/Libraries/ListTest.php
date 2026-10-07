<?php

namespace AlexRoden\LibraryApiPhp\Tests\Features\Libraries;

use AlexRoden\LibraryApiPhp\Http\Foundation\Request;
use AlexRoden\LibraryApiPhp\Models\Library;
use AlexRoden\LibraryApiPhp\Tests\Factories\LibraryFactory;
use AlexRoden\LibraryApiPhp\Tests\Features\AbstractFeaturesTestCase;

class ListTest extends AbstractFeaturesTestCase
{
    private Library $library;

    protected function setUp(): void
    {
        parent::setUp();

        $this->library = LibraryFactory::create();
    }

    public function testListLibraries(): void
    {
        $this->asAuthorizedUser();

        $response = $this->handle(
            Request::create(
                method: 'GET',
                uri: '/api/libraries',
            )
        );

        $this->assertEquals(200, $response->status());
        $this->assertSame(
            $this->library->toArray(),
            $response->json()['data'][0]->toArray()
        );
    }
}
