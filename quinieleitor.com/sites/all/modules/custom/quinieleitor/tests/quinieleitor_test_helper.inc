<?php

use carlescliment\Quinieleitor\ResultsSlip,
    carlescliment\Quinieleitor\Match,
    carlescliment\Quinieleitor\BetterSlip,
    carlescliment\Quinieleitor\Bet;

use carlescliment\Quinieleitor\Repository\BetterSlipRepository,
    carlescliment\Quinieleitor\Repository\ResultsSlipRepository,
    carlescliment\Quinieleitor\Repository\ScoreRepository;


class QuinieleitorTestHelper
{

  public function createBettingSlipForDate($date_str) {
    $slip = new ResultsSlip(null, new DateTime($date_str));
    for ($i=0; $i<MATCHES_PER_SLIP; $i++) {
      $local = $i*2;
      $visitor = ($i*2)+1;
      $slip->add(new Match(null, "TM$local-TM$visitor", null));
    }
    quinieleitor_controller()->saveSlip($slip);

    return $slip;
  }

  public function betterBetsForSlip($better, ResultsSlip $slip, $predictions)
  {
    $better_slip = new BetterSlip($better->uid);
    foreach ($slip->getMatches() as $i => $match) {
        $better_slip->add(new Bet(null, $match->getId(), $predictions[$i]));
    }

    quinieleitor_controller()->saveBet($better_slip);
  }

  public function loadResultsSlips()
  {
    global $container;
    $repository = new ResultsSlipRepository($container['database.connection']);

    return $repository->loadAll();
  }

  public function loadBetterSlip($uid, $slip_id)
  {
    global $container;
    $repository = new BetterSlipRepository($container['database.connection']);

    return $repository->load($uid, $slip_id);
  }

  public function getScoreRepository()
  {
    global $container;

    return new ScoreRepository($container['database.connection']);
  }

}
