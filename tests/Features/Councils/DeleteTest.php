<?php

namespace AlexRoden\LibraryApiPhp\Tests\Features\Councils;

use AlexRoden\LibraryApiPhp\Http\Foundation\Request;
use AlexRoden\LibraryApiPhp\Models\Council;
use AlexRoden\LibraryApiPhp\Tests\Factories\CouncilFactory;
use AlexRoden\LibraryApiPhp\Tests\Features\AbstractFeaturesTestCase;

class DeleteTest extends AbstractFeaturesTestCase
{
    private Council $council;

    protected function setUp(): void
    {
        parent::setUp();

        $this->council = CouncilFactory::create();
    }

    public function testDeleteCouncil(): void
    {
        $this->asAuthorizedUser();

        $response = $this->handle(
            Request::create(
                method: 'DELETE',
                uri: "/api/councils/{$this->council->id}",
            )
        );

        $this->assertEquals(204, $response->status());

        $council = Council::where('id', '=', $this->council->id)->first();
        $this->assertNull($council);
    }
}
