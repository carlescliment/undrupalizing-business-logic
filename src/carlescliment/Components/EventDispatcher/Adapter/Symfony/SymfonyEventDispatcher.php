<?php

namespace carlescliment\Components\EventDispatcher\Adapter\Symfony;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class SymfonyEventDispatcher implements Dispatcher
{
    private $eventDispatcher;
    
    function __construct(EventDispatcherInterface $event_dispatcher)
    {
        $this->eventDispatcher = $event_dispatcher;
    }

    public function register($event_name, $listener, $method_name)
    {
        $listener_callback = function ($event) use ($listener, $method_name) {            
            $listener->$method_name($event->getCustomEvent());
        };
        $this->eventDispatcher->addListener($event_name, $listener_callback);

        return $this;
    }
    
    public function dispatch($event_name, $event)
    {
        $symfony_event = new SymfonyEvent($event);
        $this->eventDispatcher->dispatch($event_name, $symfony_event);

        return $this;
    }
}
