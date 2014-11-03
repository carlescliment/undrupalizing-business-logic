<?php

namespace carlescliment\Quinieleitor;

class Bet
{
    private $id;
    private $matchId;
    private $prediction;


    public function __construct($id, $match_id, $prediction)
    {
        $this->id = $id;
        $this->matchId = $match_id;
        $this->prediction = $prediction;
    }

    public function getMatchId()
    {
        return $this->matchId;
    }

    public function hits(Match $match)
    {
        return $match->getResult() == $this->prediction;
    }

    public function getPrediction()
    {
        return $this->prediction;
    }
}
