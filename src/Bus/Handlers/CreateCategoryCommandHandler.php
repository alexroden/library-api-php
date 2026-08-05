<?php

namespace AlexRoden\LibraryApiPhp\Bus\Handlers;

use AlexRoden\LibraryApiPhp\Bus\CommandHandler;
use AlexRoden\LibraryApiPhp\Bus\Commands\CreateCategoryCommand;
use AlexRoden\LibraryApiPhp\Bus\Events\CreateCategoryEvent;
use AlexRoden\LibraryApiPhp\Exceptions\UndefinedClassException;
use AlexRoden\LibraryApiPhp\Models\Category;

class CreateCategoryCommandHandler extends AbstractCommandHandler implements CommandHandler
{
    /**
     * @throws UndefinedClassException
     */
    public function handle(object $command): Category
    {
        /** @var CreateCategoryCommand $command */
        $category = Category::create([
            'name' => $command->name,
        ]);

        $this->events->dispatch(
            new CreateCategoryEvent($category)
        );

        return $category;
    }
}