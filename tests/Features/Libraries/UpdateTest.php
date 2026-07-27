<?php

namespace AlexRoden\LibraryApiPhp\Tests\Features\Libraries;

use AlexRoden\LibraryApiPhp\Http\Foundation\Request;
use AlexRoden\LibraryApiPhp\Models\Library;
use AlexRoden\LibraryApiPhp\Tests\Factories\LibraryFactory;
use AlexRoden\LibraryApiPhp\Tests\Features\AbstractFeaturesTestCase;

class UpdateTest extends AbstractFeaturesTestCase
{
    private Library $library;

    protected function setUp(): void
    {
        parent::setUp();

        $this->library = LibraryFactory::create();
    }

    public function testUpdateLibrary(): void
    {
        $this->asAuthorizedUser();

        $name = 'Windermere Library';

        $response = $this->handle(
            Request::create(
                method: 'PUT',
                uri: "/api/libraries/{$this->library->id}",
                body: [
                    'name' => $name,
                ]
            )
        );

        $this->assertEquals(200, $response->status());

        $library = Library::where('name', '=', $name)->first();
        $this->assertNotNull($library);
        $this->assertSame([
            'name' => $name,
        ], [
            'name' => $library->name,
        ]);
    }
}