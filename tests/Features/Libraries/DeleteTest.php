<?php

namespace AlexRoden\LibraryApiPhp\Tests\Features\Libraries;

use AlexRoden\LibraryApiPhp\Http\Foundation\Request;
use AlexRoden\LibraryApiPhp\Models\Library;
use AlexRoden\LibraryApiPhp\Tests\Factories\LibraryFactory;
use AlexRoden\LibraryApiPhp\Tests\Features\AbstractFeaturesTestCase;

class DeleteTest extends AbstractFeaturesTestCase
{
    private Library $library;

    protected function setUp(): void
    {
        parent::setUp();

        $this->library = LibraryFactory::create();
    }

    public function testDeleteLibrary(): void
    {
        $this->asAuthorizedUser();

        $response = $this->handle(
            Request::create(
                method: 'DELETE',
                uri: "/api/libraries/{$this->library->id}",
            )
        );

        $this->assertEquals(204, $response->status());

        $library = Library::where('id', '=', $this->library->id)->first();
        $this->assertNull($library);
    }
}