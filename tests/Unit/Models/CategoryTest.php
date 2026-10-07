<?php

namespace AlexRoden\LibraryApiPhp\Tests\Unit\Models;

use AlexRoden\LibraryApiPhp\Models\Category;
use AlexRoden\LibraryApiPhp\Tests\AbstractTestCase;
use AlexRoden\LibraryApiPhp\Tests\Factories\CategoryFactory;

class CategoryTest extends AbstractTestCase
{
    private Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->category = CategoryFactory::create();
    }

    public function testCreate(): void
    {
        $category = Category::create([
            'name' => $this->faker->word(),
        ]);

        $this->assertNotNull($category->id);
    }

    public function testDelete(): void
    {
        $this->category->delete();

        $category = Category::where('id', '=', $this->category->id)->first();

        $this->assertNull($category);
    }

    public function testGet(): void
    {
        $categories = Category::where('id', '=', $this->category->id)->get();

        $this->assertCount(1, $categories);
    }

    public function testFirst(): void
    {
        $category = Category::where('id', '=', $this->category->id)->first();

        $this->assertEquals($this->category->id, $category->id);
    }

    public function testUpdate(): void
    {
        $name = $this->faker->city();

        $this->category->update([
            'name' => $name,
        ]);

        $category = $this->category->refresh();

        $this->assertSame([
            'name' => $name,
        ], [
            'name' => $category->name,
        ]);
    }

}
