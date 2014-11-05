<?php

namespace carlescliment\Quinieleitor;

use carlescliment\Quinieleitor\Repository\ResultsSlipRepository;

class ResultsSlip
{

    const MATCHES_PER_SLIP = 10;

    private $id;
    private $date;
    private $matches = array();
    private $closed = false;

    public function __construct($id, \DateTime $date)
    {
        $this->id = $id;
        $this->date = $date;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function add(Match $match)
    {
        $this->matches[] = $match;
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

    public function save(ResultsSlipRepository $repository)
    {
        $this->id = is_null($this->id) ? $repository->save($this) : $repository->update($this);
        foreach ($this->matches as $match) {
            $match->save($repository, $this);
        }

        return $this;
    }
}
