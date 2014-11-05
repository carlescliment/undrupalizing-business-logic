<?php

namespace carlescliment\Components\DataBase\Adapter\Drupal;

use carlescliment\Components\DataBase\Connection as ConnectionInterface;

class Connection implements ConnectionInterface
{
    private $dbQueryWrapper;

    public function __construct(DBQueryWrapper $db_query_wrapper)
    {
        $this->dbQueryWrapper = $db_query_wrapper;
    }

    public function execute($sentence, array $params = array())
    {
        $query = new Query($sentence, $params);
        $query->executeWith($this->dbQueryWrapper);

        return $this;
    }

    public function fetch()
    {
        return $this->dbQueryWrapper->fetchArray();
    }

    public function fetchAll()
    {
        return $this->dbQueryWrapper->fetchAll();
    }

    public function lastInsertId()
    {
        return $this->dbQueryWrapper->lastInsertId();
    }
}
