<?php

namespace AlexRoden\LibraryApiPhp\Bus\Commands;

use AlexRoden\LibraryApiPhp\Models\Category;

readonly class DeleteCategoryCommand
{
    public function __construct(
        public Category $category,
    ) {}
}