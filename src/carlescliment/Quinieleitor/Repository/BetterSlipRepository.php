<?php

namespace carlescliment\Quinieleitor\Repository;

use carlescliment\Quinieleitor\BetterSlip,
    carlescliment\Quinieleitor\Bet;

use carlescliment\Components\DataBase\Connection;


class BetterSlipRepository
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function load($user_id, $slip_id)
    {
        $this->connection->execute('SELECT b.* FROM `bets` b JOIN `matches` m ON b.match_id=m.id WHERE b.user_id=:better_id AND m.slip_id=:slip_id ORDER BY m.id ASC', array(
            ':better_id' => $user_id, 
            ':slip_id' => $slip_id));
        $better_slip = new BetterSlip($user_id, $slip_id);
        while ($bet = $this->connection->fetch()) {
            $better_slip->add(new Bet($bet['id'], $bet['match_id'], $bet['prediction']));
        }

        return $better_slip;
    }

    public function saveBet(Bet $bet, BetterSlip $slip)
    {
        $this->connection->execute('INSERT INTO `bets` (match_id, user_id, prediction) VALUES (:match_id, :user_id, :prediction)', array(
            ':match_id' => $bet->getMatchId(),
            ':user_id' => $slip->getUserId(),
            ':prediction' => $bet->getPrediction(),
        ));

        return $this->connection->lastInsertId();
    }
}
