<?php

namespace carlescliment\Components\DataBase\Adapter\Drupal;

use carlescliment\Components\DataBase\Statement as StatementInterface;

class Statement implements StatementInterface
{
    private $dbQueryWrapper;

    public function __construct(DBQueryWrapper $wrapper)
    {
        $this->dbQueryWrapper = $wrapper;
    }

    public function fetch()
    {
        return $this->dbQueryWrapper->fetchArray();
    }

    public function fetchAll()
    {
        return $this->dbQueryWrapper->fetchAll();
    }
}
