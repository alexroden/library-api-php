<?php

namespace AlexRoden\LibraryApiPhp\Tests\Features\Categories;

use AlexRoden\LibraryApiPhp\Http\Foundation\Request;
use AlexRoden\LibraryApiPhp\Models\Category;
use AlexRoden\LibraryApiPhp\Models\Council;
use AlexRoden\LibraryApiPhp\Tests\Factories\CategoryFactory;
use AlexRoden\LibraryApiPhp\Tests\Features\AbstractFeaturesTestCase;

class UpdateTest extends AbstractFeaturesTestCase
{
    private Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->category = CategoryFactory::create();
    }

    public function testUpdateCategory(): void
    {
        $this->asAuthorizedUser();

        $name = 'Drama';

        $response = $this->handle(
            Request::create(
                method: 'PUT',
                uri: "/api/categories/{$this->category->id}",
                body: [
                    'name' => $name,
                ]
            )
        );

        $this->assertEquals(200, $response->status());

        $category = Category::where('name', '=', $name)->first();
        $this->assertNotNull($category);
        $this->assertSame([
            'name' => $name,
        ], [
            'name' => $category->name,
        ]);
    }
}
