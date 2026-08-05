<?php

namespace AlexRoden\LibraryApiPhp\Tests\Features\Categories;

use AlexRoden\LibraryApiPhp\Http\Foundation\Request;
use AlexRoden\LibraryApiPhp\Models\Category;
use AlexRoden\LibraryApiPhp\Tests\Features\AbstractFeaturesTestCase;

class CreateTest extends AbstractFeaturesTestCase
{
    public function testCreateCategory(): void
    {
        $this->asAuthorizedUser();

        $name = 'Test Category';

        $response = $this->handle(
            Request::create(
                method: 'POST',
                uri: '/api/categories',
                body: [
                    'name' => $name,
                ]
            )
        );

        $this->assertEquals(201, $response->status());

        $category = Category::where('name', '=', $name)->first();
        $this->assertNotNull($category);
        $this->assertEquals($name, $category->name);
    }
}