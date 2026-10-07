<?php

namespace AlexRoden\LibraryApiPhp\Tests\Unit\Http\Requests;

use AlexRoden\LibraryApiPhp\Http\Exceptions\ValidationException;
use AlexRoden\LibraryApiPhp\Http\Requests\UpdateStockRequest;
use AlexRoden\LibraryApiPhp\Tests\AbstractTestCase;

class UpdateStockRequestTest extends AbstractTestCase
{
    public function testRulesPassed(): void
    {
        $req = new UpdateStockRequest(body: ['quantity' => 4]);

        $this->assertSame(['quantity' => 4], $req->validated());
    }

    public function testQuantityOfZeroIsAccepted(): void
    {
        $req = new UpdateStockRequest(body: ['quantity' => 0]);

        $this->assertSame(['quantity' => 0], $req->validated());
    }

    public function testLibraryAndBookAreNotPassedThrough(): void
    {
        $req = new UpdateStockRequest(body: [
            'library_id' => 99,
            'book_id' => 98,
            'quantity' => 4,
        ]);

        $this->assertSame(['quantity' => 4], $req->validated());
    }

    public function testQuantityIsRequired(): void
    {
        $this->expectException(ValidationException::class);

        new UpdateStockRequest(body: []);
    }

    public function testQuantityCannotBeNegative(): void
    {
        $this->expectException(ValidationException::class);

        new UpdateStockRequest(body: ['quantity' => -1]);
    }

    public function testQuantityMustBeAWholeNumber(): void
    {
        $this->expectException(ValidationException::class);

        new UpdateStockRequest(body: ['quantity' => 'abc']);
    }
}
