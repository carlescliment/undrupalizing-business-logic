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
        $statement = $this->connection->execute('SELECT * FROM `betting_slips` WHERE date>NOW() AND closed=0 ORDER BY date ASC LIMIT 1');
        if ($slip_data = $statement->fetch()) {
            return $this->buildSlipFromData($slip_data);
        }

        return null;
    }

    public function load($slip_id)
    {
        $statement = $this->connection->execute('SELECT * FROM `betting_slips` WHERE id=:id', array(
            ':id' => $slip_id));
        $slip_data = $statement->fetch();
        if ($slip_data) {
            return $this->buildSlipFromData($slip_data);
        }

        return null;
    }

    public function loadAll()
    {
        $slips = array();
        $statement = $this->connection->execute('SELECT * FROM `betting_slips` ORDER BY date ASC');
        while ($slip_data = $statement->fetch()) {
            $slips[] = $this->buildSlipFromData($slip_data);
        }

        return $slips;
    }

    public function save(ResultsSlip $slip)
    {
        $this->connection->execute('INSERT INTO `betting_slips` (date, closed) VALUES (:date, :closed)', array(
            ':date' => $slip->getDate()->format('Y-m-d'),
            ':closed' => (int)$slip->isClosed(),
        ));

        return $this->connection->lastInsertId();
    }

    public function update(ResultsSlip $slip)
    {
        $this->connection->execute('UPDATE `betting_slips` set date = :date, closed = :closed WHERE id = :id', array(
            ':date' => $slip->getDate()->format('Y-m-d'),
            ':closed' => (int)$slip->isClosed(),
            ':id' => $slip->getId(),
        ));

        return $slip->getId();
    }

    public function saveMatch(Match $match, ResultsSlip $slip)
    {
        $this->connection->execute('INSERT INTO `matches` (slip_id, name, result) VALUES (:slip_id, :name, :result)', array(
            ':slip_id' => $slip->getId(),
            ':name' => $match->getName(),
            ':result' => $match->getResult(),
        ));

        return $this->connection->lastInsertId();
    }

    public function updateMatch(Match $match)
    {
        $this->connection->execute('UPDATE `matches` SET name = :name, result = :result WHERE id = :id', array(
            ':name' => $match->getName(),
            ':result' => $match->getResult(),
            ':id' => $match->getId(),
        ));

        return $match->getId();
    }

    private function buildSlipFromData(array $slip_data)
    {
        $slip = new ResultsSlip($slip_data['id'], new \DateTime($slip_data['date']));
        if ($slip_data['closed']) {
            $slip->close();
        }
        $this->loadSlipMatches($slip);

        return $slip;
    }

    private function loadSlipMatches(ResultsSlip $slip)
    {
        $statement = $this->connection->execute('SELECT * FROM `matches` WHERE slip_id=:slip_id ORDER BY id ASC', array(
            ':slip_id' => $slip->getId()));
        while ($match = $statement->fetch()) {
            $slip->add(new Match($match['id'], $match['name'], $match['result']));
        }
    }
}
