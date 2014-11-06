<?php

namespace carlescliment\Components\EventDispatcher;

interface Dispatcher
{
    public function dispatch($event_name, $event);
    public function register($event_name, $listener, $method_name);
}
