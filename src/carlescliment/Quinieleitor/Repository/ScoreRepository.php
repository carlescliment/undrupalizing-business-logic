<?php

namespace carlescliment\Quinieleitor\Repository;

use carlescliment\Quinieleitor\Score\Score;
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

    public function loadByUser($user_id)
    {
        $this->connection->execute('SELECT * FROM `better_points` WHERE user_id=:user_id', array(
            ':user_id' => $user_id,
        ));

        if ($current_score = $this->connection->fetch()) {
            return new Score($user_id, $current_score['points']);
        }

        return new Score($user_id, 0);
    }

    public function loadHallOfFame($members)
    {
        $this->connection->execute('SELECT * FROM `better_points` p JOIN `users` u ON p.user_id=u.uid ORDER BY points DESC LIMIT :members', array(
        ':members' => $members));
        $hall_of_fame = array();
        while ($row = $this->connection->fetch()) {
            $hall_of_fame[] = new Score($row['user_id'], $row['points']);
        }

        return $hall_of_fame;
    }
}
