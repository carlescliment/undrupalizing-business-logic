<?php

namespace carlescliment\Quinieleitor;

use carlescliment\Quinieleitor\ResultsSlip,
    carlescliment\Quinieleitor\Match,
    carlescliment\Quinieleitor\BetterSlip,
    carlescliment\Quinieleitor\Bet;

use carlescliment\Quinieleitor\Repository\BetterSlipRepository,
    carlescliment\Quinieleitor\Repository\ResultsSlipRepository,
    carlescliment\Quinieleitor\Repository\ScoreRepository;

use carlescliment\Quinieleitor\Controller\ApplicationController;


class Helper
{
    private $container;
    private $controller;

    public function __construct(\Pimple\Container $container)
    {
        $this->container = $container;
        $this->controller = new ApplicationController($container);
    }

    public function createBettingSlipForDate($date_str) {
        $slip = new ResultsSlip(null, new \DateTime($date_str));
        for ($i=0; $i<ResultsSlip::MATCHES_PER_SLIP; $i++) {
            $local = $i*2;
            $visitor = ($i*2)+1;
            $slip->add(new Match(null, "TM$local-TM$visitor", null));
        }
        $this->controller->saveSlip($slip);

        return $slip;
    }

    public function betterBetsForSlip($better_id, ResultsSlip $slip, $predictions)
    {
        $better_slip = new BetterSlip($better_id);
        foreach ($slip->getMatches() as $i => $match) {
            $better_slip->add(new Bet(null, $match->getId(), $predictions[$i]));
        }

        $this->controller->saveBet($better_slip);
    }

    public function loadResultsSlips()
    {
        $repository = new ResultsSlipRepository($this->container['database.connection']);

        return $repository->loadAll();
    }

    public function loadBetterSlip($uid, $slip_id)
    {
        $repository = new BetterSlipRepository($this->container['database.connection']);

        return $repository->load($uid, $slip_id);
    }

    public function getScoreRepository()
    {
        return new ScoreRepository($this->container['database.connection']);
    }

}
