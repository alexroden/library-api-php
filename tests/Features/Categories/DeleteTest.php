<?php

namespace AlexRoden\LibraryApiPhp\Tests\Features\Categories;

use AlexRoden\LibraryApiPhp\Http\Foundation\Request;
use AlexRoden\LibraryApiPhp\Models\Category;
use AlexRoden\LibraryApiPhp\Tests\Factories\CategoryFactory;
use AlexRoden\LibraryApiPhp\Tests\Features\AbstractFeaturesTestCase;

class DeleteTest extends AbstractFeaturesTestCase
{
    private Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->category = CategoryFactory::create();
    }

    public function testDeleteCategory(): void
    {
        $this->asAuthorizedUser();

        $response = $this->handle(
            Request::create(
                method: 'DELETE',
                uri: "/api/categories/{$this->category->id}",
            )
        );

        $this->assertEquals(204, $response->status());

        $category = Category::where('id', '=', $this->category->id)->first();
        $this->assertNull($category);
    }
}
