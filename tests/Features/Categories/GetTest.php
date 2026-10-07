<?php

namespace AlexRoden\LibraryApiPhp\Tests\Features\Categories;

use AlexRoden\LibraryApiPhp\Http\Foundation\Request;
use AlexRoden\LibraryApiPhp\Models\Category;
use AlexRoden\LibraryApiPhp\Tests\Factories\CategoryFactory;
use AlexRoden\LibraryApiPhp\Tests\Features\AbstractFeaturesTestCase;

class GetTest extends AbstractFeaturesTestCase
{
    private Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->category = CategoryFactory::create();
    }

    public function testGetCategory(): void
    {
        $this->asAuthorizedUser();

        $response = $this->handle(
            Request::create(
                method: 'GET',
                uri: "/api/categories/{$this->category->id}",
            )
        );

        $this->assertEquals(200, $response->status());
        $this->assertSame(
            $this->category->toArray(),
            $response->json()['data']->toArray()
        );
    }
}
