<?php

namespace carlescliment\Components\DataBase\Adapter\Drupal;

use carlescliment\Components\DataBase\Connection as ConnectionInterface;

class Connection implements ConnectionInterface
{
    private $dbQueryWrapper;

    public function execute($sentence, array $params = array())
    {
        $this->dbQueryWrapper = new DBQueryWrapper();
        $query = new Query($sentence, $params);
        $query->executeWith($this->dbQueryWrapper);

        return new Statement($this->dbQueryWrapper);
    }

    public function lastInsertId()
    {
        return $this->dbQueryWrapper->lastInsertId();
    }
}
