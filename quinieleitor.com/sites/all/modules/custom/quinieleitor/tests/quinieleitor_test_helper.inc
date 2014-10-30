<?php

class QuinieleitorTestHelper
{

  public function createBettingSlipForDate($date_str) {
    $slip = new stdClass();
    $slip->date = $date_str;
    $slip->matches = array();
    for ($i=0; $i<MATCHES_PER_SLIP; $i++) {
      $local = $i*2;
      $visitor = ($i*2)+1;
      $match = new stdClass();
      $match->name = "TM$local-TM$visitor";
      $slip->matches[$i] = $match;
    }
    quinieleitor_save_betting_slip($slip);

    return $slip;
  }


  public function betterBetsForSlip($better, $slip, $predictions)
  {
    $better_slip = new stdClass();
    $better_slip->matches = array();
    foreach (array_values($slip->matches) as $i => $match) {
        $bet = new stdClass();
        $bet->match_id = $match->id;
        $bet->user_id = $better->uid;
        $bet->prediction = $predictions[$i];
        $better_slip->matches[] = $bet;
    }
    quinieleitor_save_bet($better_slip);
  }
}
