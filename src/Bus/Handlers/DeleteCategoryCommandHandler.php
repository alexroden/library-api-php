<?php

namespace AlexRoden\LibraryApiPhp\Bus\Handlers;

use AlexRoden\LibraryApiPhp\Bus\CommandHandler;
use AlexRoden\LibraryApiPhp\Bus\Commands\DeleteCategoryCommand;
use AlexRoden\LibraryApiPhp\Bus\Events\DeleteCategoryEvent;

class DeleteCategoryCommandHandler extends AbstractCommandHandler implements CommandHandler
{
    public function handle(object $command): null
    {
        /** @var DeleteCategoryCommand $command */
        $category = $command->category;

        $category->delete();

        $this->events->dispatch(
            new DeleteCategoryEvent($category)
        );

        return null;
    }
}
