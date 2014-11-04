<?php

namespace carlescliment\Quinieleitor;

class ResultsSlip
{

    const MATCHES_PER_SLIP = 10;

    private $id;
    private $matches = array();
    private $closed = false;

    public function __construct($id = null)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function add(Match $match)
    {
        $this->matches[$match->getId()] = $match;
    }

    public function getMatches()
    {
        return $this->matches;
    }

    public function close()
    {
        $this->closed = true;
    }

    public function isClosed()
    {
        return $this->closed;
    }
}
