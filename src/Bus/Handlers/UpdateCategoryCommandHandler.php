<?php

namespace AlexRoden\LibraryApiPhp\Bus\Handlers;

use AlexRoden\LibraryApiPhp\Bus\CommandHandler;
use AlexRoden\LibraryApiPhp\Bus\Commands\UpdateCategoryCommand;
use AlexRoden\LibraryApiPhp\Bus\Events\UpdateCategoryEvent;
use AlexRoden\LibraryApiPhp\Exceptions\UndefinedClassException;
use AlexRoden\LibraryApiPhp\Models\Category;

class UpdateCategoryCommandHandler extends AbstractCommandHandler implements CommandHandler
{
    /**
     * @throws UndefinedClassException
     */
    public function handle(object $command): Category
    {
        /** @var UpdateCategoryCommand $command */
        $command->category->update([
            'name' => $command->name,
        ]);

        $category = $command->category->refresh();

        $this->events->dispatch(
            new UpdateCategoryEvent($category)
        );

        return $category;
    }
}
