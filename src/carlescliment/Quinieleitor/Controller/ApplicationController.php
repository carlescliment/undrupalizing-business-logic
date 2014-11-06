<?php

namespace carlescliment\Quinieleitor\Controller;

use Pimple\Container;

use carlescliment\Quinieleitor\Repository\ScoreRepository,
    carlescliment\Quinieleitor\Repository\ResultsSlipRepository,
    carlescliment\Quinieleitor\Repository\BetterSlipRepository
    ;
use carlescliment\Quinieleitor\BetterSlip,
    carlescliment\Quinieleitor\ResultsSlip
    ;

class ApplicationController
{
    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function getCurrentSlip()
    {
        return $this->getResultsSlipRepository()->loadCurrent();
    }

    public function loadSlip($slip_id)
    {
        return $this->getResultsSlipRepository()->load($slip_id);
    }

    public function saveSlip(ResultsSlip $slip)
    {
        $slip->save($this->getResultsSlipRepository());

        return $this;
    }

    public function saveBet(BetterSlip $slip)
    {
        $slip->save($this->getBetterSlipRepository());

        return $this;
    }

    public function getHallOfFame($members)
    {
        return $this->getScoreRepository()->loadHallOfFame($members);
    }

    public function resolve($results_slip_id, array $matches)
    {
        $repository = $this->getResultsSlipRepository();
        $slip = $repository->load($results_slip_id);
        foreach ($matches as $match_id => $result) {
            $slip->resolve($match_id, $result);
        }
        $slip->close();
        $slip->save($repository);

        return $this;
    }

    private function getScoreRepository()
    {
        return new ScoreRepository($this->container['database.connection']);
    }

    private function getResultsSlipRepository()
    {
        return new ResultsSlipRepository($this->container['database.connection']);
    }

    private function getBetterSlipRepository()
    {
        return new BetterSlipRepository($this->container['database.connection']);
    }
}
