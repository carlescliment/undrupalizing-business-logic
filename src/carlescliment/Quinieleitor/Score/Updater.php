<?php

namespace carlescliment\Quinieleitor\Score;

use carlescliment\Quinieleitor\Repository\ScoreRepository;
use carlescliment\Quinieleitor\ResultsSlip,
    carlescliment\Quinieleitor\BetterSlips;

class Updater
{
    private $scoreRepository;
    private $prizeTable;

    public function __construct(ScoreRepository $repository, array $prize_table)
    {
        $this->scoreRepository = $repository;
        $this->prizeTable = $prize_table;
    }


    public function update(ResultsSlip $results_slip, BetterSlips $better_slips)
    {
        $calculator = new PrizeCalculator(quinieleitor_get_prize_table());
        $better_slips->calculatePrizes($results_slip, $calculator);
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
