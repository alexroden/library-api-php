<?php

namespace AlexRoden\LibraryApiPhp\Tests\Features\Councils;

use AlexRoden\LibraryApiPhp\Http\Foundation\Request;
use AlexRoden\LibraryApiPhp\Models\Council;
use AlexRoden\LibraryApiPhp\Tests\Factories\CouncilFactory;
use AlexRoden\LibraryApiPhp\Tests\Features\AbstractFeaturesTestCase;

class ListTest extends AbstractFeaturesTestCase
{
    private Council $council;

    protected function setUp(): void
    {
        parent::setUp();

        $this->council = CouncilFactory::create();
    }

    public function testGetCouncil(): void
    {
        $this->asAuthorizedUser();

        $response = $this->handle(
            Request::create(
                method: 'GET',
                uri: '/api/councils',
            )
        );

        $this->assertEquals(200, $response->status());
        $this->assertSame(
            $this->council->toArray(),
            $response->json()['data'][0]->toArray()
        );
    }
}