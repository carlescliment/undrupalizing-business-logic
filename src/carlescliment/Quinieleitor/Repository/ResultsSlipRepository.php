<?php

namespace carlescliment\Quinieleitor\Repository;

use carlescliment\Quinieleitor\ResultsSlip,
    carlescliment\Quinieleitor\Match;

use carlescliment\Components\DataBase\Connection;


class ResultsSlipRepository
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function loadCurrent()
    {
        $this->connection->execute('SELECT * FROM `betting_slips` WHERE date>NOW() AND closed=0 ORDER BY date ASC LIMIT 1');
        if ($slip_data = $this->connection->fetch()) {
            $slip = new ResultsSlip($slip_data['id']);
            $this->loadSlipMatches($slip);
            return $slip;
        }

        return null;
    }

    public function load($slip_id)
    {
        $this->connection->execute('SELECT * FROM `betting_slips` WHERE id=:id', array(
            ':id' => $slip_id));
        $slip_data = $this->connection->fetch();
        if ($slip_data) {
            $slip = new ResultsSlip($slip_data['id']);
            if ($slip_data['closed']) {
                $slip->close();
            }
            $this->loadSlipMatches($slip);

            return $slip;
        }

        return null;
    }

    public function loadAll()
    {
        $slips = array();
        $this->connection->execute('SELECT * FROM {betting_slips} ORDER BY date ASC');
        while ($slip_data = $this->connection->fetch()) {
            $slip = new ResultsSlip($slip_data['id']);
            $this->loadSlipMatches($slip);
            $slips[] = $slip;
        }

        return $slips;
    }

    private function loadSlipMatches(ResultsSlip $slip)
    {
        $this->connection->execute('SELECT * FROM `matches` WHERE slip_id=:slip_id ORDER BY id ASC', array(
            ':slip_id' => $slip->getId()));
        while ($match = $this->connection->fetch()) {
            $slip->add(new Match($match['id'], $match['name'], $match['result']));
        }
    }
}
