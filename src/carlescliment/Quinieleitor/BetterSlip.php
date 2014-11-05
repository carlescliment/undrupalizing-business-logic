<?php

namespace carlescliment\Quinieleitor;

use carlescliment\Quinieleitor\Repository\BetterSlipRepository;

class BetterSlip
{
    private $userId;
    private $slipId;
    private $bets = array();
    private $prize = 0;

    public function __construct($user_id, $slip_id)
    {
        $this->userId = $user_id;
        $this->slipId = $slip_id;
    }

    public function add(Bet $bet)
    {
        $this->bets[$bet->getMatchId()] = $bet;
    }

    public function calculatePrizeWith(ResultsSlip $results_slip, PrizeCalculator $calculator, $num_slips, array $hits_count)
    {
        $hits = $this->getHits($results_slip);
        $share_with_betters = isset($hits_count[$hits]) ? $hits_count[$hits] : 1;
        $this->prize = $calculator->calculatePrize($hits, $num_slips, $share_with_betters);
    }

    public function getHits(ResultsSlip $results_slip)
    {
        $hits = 0;
        foreach ($results_slip->getMatches() as $match) {
            $hits += $this->bets[$match->getId()]->hits($match);
        }

        return $hits;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getPrize()
    {
        return $this->prize;
    }

    public function getBets()
    {
        return $this->bets;
    }

    public function save(BetterSlipRepository $repository)
    {
        foreach ($this->bets as $bet) {
            $bet->save($repository, $this);
        }

        return $this;
    }
}
