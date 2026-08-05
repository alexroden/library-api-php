<?php

namespace AlexRoden\LibraryApiPhp\Bus\Commands;

use AlexRoden\LibraryApiPhp\Models\Category;

readonly class UpdateCategoryCommand
{
    public function __construct(
        public Category $category,
        public string $name,
    ) {}
}