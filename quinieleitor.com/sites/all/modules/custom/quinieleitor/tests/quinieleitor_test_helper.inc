<?php

use carlescliment\Quinieleitor\ResultsSlip,
    carlescliment\Quinieleitor\Match,
    carlescliment\Quinieleitor\BetterSlip,
    carlescliment\Quinieleitor\Bet;


class QuinieleitorTestHelper
{

  public function createBettingSlipForDate($date_str) {
    $slip = new ResultsSlip(null, new DateTime($date_str));
    for ($i=0; $i<MATCHES_PER_SLIP; $i++) {
      $local = $i*2;
      $visitor = ($i*2)+1;
      $slip->add(new Match(null, "TM$local-TM$visitor", null));
    }
    quinieleitor_save_betting_slip($slip);

    return $slip;
  }

  public function betterBetsForSlip($better, ResultsSlip $slip, $predictions)
  {
    $better_slip = new BetterSlip($better->uid);
    foreach ($slip->getMatches() as $i => $match) {
        $better_slip->add(new Bet(null, $match->getId(), $predictions[$i]));
    }

    quinieleitor_save_better_slip($better_slip);
  }
}
