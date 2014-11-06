<?php

namespace carlescliment\Quinieleitor\Score;

class PrizeCalculator
{
    private $prizeTable;

    public function __construct(array $prize_table)
    {
        $this->prizeTable = $prize_table;
    }

    public function calculatePrize($hits, $num_players, $share_with)
    {
      if (isset($this->prizeTable['prizes'][$hits])) {
        return $num_players * $this->prizeTable['slip_value'] * $this->prizeTable['prizes'][$hits] / $share_with;
      }

      return 0;
    }
}
