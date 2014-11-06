<?php

namespace carlescliment\Quinieleitor\EventDispatcher\Listener;

use carlescliment\Quinieleitor\EventDispatcher\Event\ResultsSlipEvent;
use carlescliment\Quinieleitor\Repository\BetterSlipRepository;
use carlescliment\Quinieleitor\Score\Updater;

class ScoreListener
{
    private $betterSlipRepository;
    private $updater;

    public function __construct(BetterSlipRepository $better_slip_repository, Updater $updater)
    {
        $this->betterSlipRepository = $better_slip_repository;
        $this->updater = $updater;
    }

    
    public function onSlipResolved(ResultsSlipEvent $event)
    {
        $slip = $event->getSlip();
        $better_slips = $this->betterSlipRepository->loadAllByResultsSlip($slip);
        $this->updater->update($slip, $better_slips);

        return $this;
    }
}
