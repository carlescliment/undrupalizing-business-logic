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
}
