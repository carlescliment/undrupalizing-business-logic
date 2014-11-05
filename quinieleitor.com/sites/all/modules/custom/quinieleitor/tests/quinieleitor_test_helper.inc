<?php

use carlescliment\Quinieleitor\ResultsSlip,
    carlescliment\Quinieleitor\Match;

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
    $better_slip = new stdClass();
    $better_slip->matches = array();
    foreach ($slip->getMatches() as $i => $match) {
        $bet = new stdClass();
        $bet->match_id = $match->getId();
        $bet->user_id = $better->uid;
        $bet->prediction = $predictions[$i];
        $better_slip->matches[] = $bet;
    }
    quinieleitor_save_bet($better_slip);
  }
}
