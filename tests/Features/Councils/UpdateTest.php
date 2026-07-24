<?php

namespace AlexRoden\LibraryApiPhp\Tests\Features\Councils;

use AlexRoden\LibraryApiPhp\Http\Foundation\Request;
use AlexRoden\LibraryApiPhp\Models\Council;
use AlexRoden\LibraryApiPhp\Tests\Factories\CouncilFactory;
use AlexRoden\LibraryApiPhp\Tests\Features\AbstractFeaturesTestCase;

class UpdateTest extends AbstractFeaturesTestCase
{
    private Council $council;

    protected function setUp(): void
    {
        parent::setUp();

        $this->council = CouncilFactory::create();
    }

    public function testUpdateCouncil(): void
    {
        $this->asAuthorizedUser();

        $name = 'Windermere';

        $response = $this->handle(
            Request::create(
                method: 'PUT',
                uri: "/api/councils/{$this->council->id}",
                body: [
                    'name' => $name,
                ]
            )
        );

        $this->assertEquals(200, $response->status());

        $council = Council::where('name', '=', $name)->first();
        $this->assertNotNull($council);
        $this->assertSame([
            'name' => $name,
        ], [
            'name' => $council->name,
        ]);
    }
}