<?php

namespace carlescliment\Quinieleitor\Controller;

use Pimple\Container;

use carlescliment\Quinieleitor\Repository\ScoreRepository
    ;

class ApplicationController
{
    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function getHallOfFame($members)
    {
        return $this->getScoreRepository()->loadHallOfFame($members);
    }


    private function getScoreRepository()
    {
        return new ScoreRepository($this->container['database.connection']);
    }
}
