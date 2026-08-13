<?php

namespace AlexRoden\LibraryApiPhp\Bus\Events;

use AlexRoden\LibraryApiPhp\Models\Category;

final readonly class UpdateCategoryEvent
{
    public function __construct(
        public Category $category,
    ) {
    }
}
