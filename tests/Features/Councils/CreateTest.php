<?php

namespace AlexRoden\LibraryApiPhp\Tests\Features\Councils;

use AlexRoden\LibraryApiPhp\Http\Foundation\Request;
use AlexRoden\LibraryApiPhp\Models\Council;
use AlexRoden\LibraryApiPhp\Tests\Features\AbstractFeaturesTestCase;

class CreateTest extends AbstractFeaturesTestCase
{
    public function testCreateCouncil(): void
    {
        $this->asAuthorizedUser();

        $name = 'Test Council';

        $response = $this->handle(
            Request::create(
                method: 'POST',
                uri: '/api/councils',
                body: [
                    'name' => $name,
                ]
            )
        );

        $this->assertEquals(201, $response->status());

        $council = Council::where('name', '=', $name)->first();
        $this->assertNotNull($council);
        $this->assertEquals($name, $council->name);
    }
}
