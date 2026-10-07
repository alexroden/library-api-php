<?php

namespace AlexRoden\LibraryApiPhp\Bus\Events;

use AlexRoden\LibraryApiPhp\Models\Category;

final readonly class CreateCategoryEvent
{
    public function __construct(
        public Category $category,
    ) {
    }
}
