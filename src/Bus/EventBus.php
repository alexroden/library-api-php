<?php

namespace AlexRoden\LibraryApiPhp\Bus;

class EventBus
{
    /**
     * @var array<class-string, EventListener[]>
     */
    protected array $listeners = [];

    public function listen(
        string $event,
        EventListener $listener,
    ): void {
        $this->listeners[$event][] = $listener;
    }

    public function dispatch(object $event): void
    {
        foreach ($this->listeners[$event::class] ?? [] as $listener) {
            $listener->handle($event);
        }
    }
}