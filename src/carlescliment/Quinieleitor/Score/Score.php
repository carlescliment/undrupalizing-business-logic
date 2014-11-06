<?php

namespace carlescliment\Quinieleitor\Score;

use carlescliment\Quinieleitor\Repository\ScoreRepository;

class Score
{
    private $userId;
    private $points;

    public function __construct($user_id, $points)
    {
        $this->userId = $user_id;
        $this->points = $points;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getPoints()
    {
        return $this->points;
    }

    public function save(ScoreRepository $repository)
    {
        $repository->save($this);

        return $this;
    }

    public function sum(Score $to_sum)
    {
        return new Score($this->userId, $to_sum->getPoints() + $this->getPoints());
    }
}
