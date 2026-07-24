<?php

namespace AlexRoden\LibraryApiPhp\Tests\Unit\Models;

use AlexRoden\LibraryApiPhp\Models\Council;
use AlexRoden\LibraryApiPhp\Tests\AbstractTestCase;
use AlexRoden\LibraryApiPhp\Tests\Factories\CouncilFactory;

class CouncilTest extends AbstractTestCase
{
    private Council $council;

    protected function setUp(): void
    {
        parent::setUp();

        $this->council = CouncilFactory::create();
    }

    public function testCreate(): void
    {
        $council = Council::create([
            'name' => $this->faker->city(),
        ]);

        $this->assertNotNull($council->id);
    }

    public function testDelete(): void
    {
        $this->council->delete();

        $council = Council::where('id', '=', $this->council->id)->first();

        $this->assertNull($council);
    }

    public function testGet(): void
    {
        $councils = Council::where('id', '=', $this->council->id)->get();

        $this->assertCount(1, $councils);
    }

    public function testFirst(): void
    {
        $council = Council::where('id', '=', $this->council->id)->first();

        $this->assertEquals($this->council->id, $council->id);
    }

    public function testUpdate(): void
    {
        $name = $this->faker->city();

        $this->council->update([
            'name' => $name,
        ]);

        $council = $this->council->refresh();

        $this->assertSame([
            'name' => $name,
        ], [
            'name' => $council->name,
        ]);
    }

}