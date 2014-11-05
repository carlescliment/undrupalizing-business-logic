<?php

namespace carlescliment\Quinieleitor\Repository;

use carlescliment\Quinieleitor\Score;
use carlescliment\Components\DataBase\Connection;

class ScoreRepository
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function save(Score $score)
    {
        $this->connection->execute('INSERT INTO `better_points` (user_id, points) VALUES (:user_id, :points) ON DUPLICATE KEY UPDATE points=:points', array(
            ':user_id' => $score->getUserId(), 
            ':points' => $score->getPoints()));

        return $this;
    }
}
