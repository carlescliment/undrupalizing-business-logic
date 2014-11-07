<?php

namespace carlescliment\Components\DataBase\Adapter\PDO;

use carlescliment\Components\DataBase\Statement as StatementInterface;

class Statement implements StatementInterface
{
    private $statement;

    public function __construct(\PDOStatement $statement)
    {
        $this->statement = $statement;
    }

    public function fetch()
    {
        return $this->statement->fetch(\PDO::FETCH_ASSOC);
    }

    public function fetchAll()
    {
        return $this->statement->fetchAll(\PDO::FETCH_ASSOC);
    }
}
