<?php

namespace carlescliment\Quinieleitor\Score;

use carlescliment\Quinieleitor\Repository\ScoreRepository;
use carlescliment\Quinieleitor\ResultsSlip,
    carlescliment\Quinieleitor\BetterSlips
    ;

class Updater
{
    private $scoreRepository;
    private $calculator;

    public function __construct(ScoreRepository $repository, PrizeCalculator $calculator)
    {
        $this->scoreRepository = $repository;
        $this->calculator = $calculator;
    }

    public function update(ResultsSlip $results_slip, BetterSlips $better_slips)
    {
        $better_slips->calculatePrizes($results_slip, $this->calculator);
        foreach ($better_slips->all() as $better_slip) {
            $this->addBetterPoints($better_slip->getUserId(), $better_slip->getPrize());
        }

        return $this;
    }

    private function addBetterPoints($uid, $points)
    {
        $score = new Score($uid, $points);
        $score->sum($this->scoreRepository->loadByUser($uid))->save($this->scoreRepository);
    }
}
