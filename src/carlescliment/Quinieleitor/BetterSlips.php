<?php

namespace carlescliment\Quinieleitor;

class BetterSlips
{
    private $slips;

    public function __construct(array $slips = array())
    {
        $this->slips = $slips;
    }

    public function add(BetterSlip $slip)
    {
        $this->slips[] = $slip;
    }

    public function all()
    {
        return $this->slips;
    }

    public function calculatePrizes(ResultsSlip $results_slip, PrizeCalculator $calculator)
    {
        $hits_count = $this->getHitsCount($results_slip);
        foreach ($this->slips as $better_slip) {
            $better_slip->calculatePrizeWith($results_slip, $calculator, count($this->slips), $hits_count);
        }
    }

    private function getHitsCount(ResultsSlip $results_slip)
    {
      $hits_count = array();
      foreach ($this->slips as $better_slip) {
        $player_hits = $better_slip->getHits($results_slip);
        if (!isset($hits_count[$player_hits])) {
          $hits_count[$player_hits] = 0;
        }
        $hits_count[$player_hits]++;
      }

      return $hits_count;
    }
}
