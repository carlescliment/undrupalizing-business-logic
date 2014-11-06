<?php

namespace carlescliment\Quinieleitor\EventDispatcher\Event;

use carlescliment\Quinieleitor\ResultsSlip;

class ResultsSlipEvent
{
    private $slip;

    public function __construct(ResultsSlip $slip)
    {
        $this->slip = $slip;
    }

    public function getSlip()
    {
        return $this->slip;
    }
}
