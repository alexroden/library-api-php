<?php

namespace AlexRoden\LibraryApiPhp\Tests\Features\Libraries;

use AlexRoden\LibraryApiPhp\Http\Foundation\Request;
use AlexRoden\LibraryApiPhp\Models\Library;
use AlexRoden\LibraryApiPhp\Tests\Features\AbstractFeaturesTestCase;

class CreateTest extends AbstractFeaturesTestCase
{
    public function testCreateLibrary(): void
    {
        $this->asAuthorizedUser();

        $name = 'Test Library';

        $response = $this->handle(
            Request::create(
                method: 'POST',
                uri: '/api/libraries',
                body: [
                    'name' => $name,
                ]
            )
        );

        $this->assertEquals(201, $response->status());

        $library = Library::where('name', '=', $name)->first();
        $this->assertNotNull($library);
        $this->assertEquals($name, $library->name);
    }
}