<?php

namespace carlescliment\Components\EventDispatcher\Adapter\Symfony;

use Symfony\Component\EventDispatcher\Event;

/**
 * Description of SymfonyEvent
 *
 * @author marta
 */
class SymfonyEvent extends Event
{
    
    private $customEvent;
    
    public function __construct($custom_event)
    {
        $this->customEvent = $custom_event; 
    }
    
    public function getCustomEvent()
    {
        return $this->customEvent;
    }  
    
    
}
