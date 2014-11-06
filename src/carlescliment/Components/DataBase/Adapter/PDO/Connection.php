<?php

namespace carlescliment\Components\DataBase\Adapter\PDO;

use carlescliment\Components\DataBase\Connection as ConnectionInterface;
use carlescliment\Components\DataBase\Exception\DataBaseException;

class Connection implements ConnectionInterface
{
    private $connection;
    private $lastStatement;

    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

    public function execute($query, array $params = array())
    {
        $this->lastStatement = $this->connection->prepare($query);
        $this->prepareParams($params);
        $success = $this->lastStatement->execute();
        if (!$success) {
            $error = $this->lastStatement->errorInfo();
            throw new DataBaseException($error[2] . ' executing ' . $query);
        }

        return $this;
    }

    private function prepareParams(array $params = array())
    {
        if (!empty($params))
        {
            foreach ($params as $key => $value)
            {
                $this->lastStatement->bindValue($key, $value, $this->getParamType($value));
            }
        }
    }

    public function getParamType($value)
    {
        if (is_null($value)) {
            return \PDO::PARAM_NULL;
        }
        if (is_bool($value)) {
            return \PDO::PARAM_BOOL;
        }
        if (is_int($value)) {
            return \PDO::PARAM_INT;
        }
        return \PDO::PARAM_STR;
    }

    public function fetch()
    {
        return $this->lastStatement->fetch(\PDO::FETCH_ASSOC);
    }

    public function fetchAll()
    {
        return $this->lastStatement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function lastInsertId()
    {
        return $this->connection->lastInsertId();
    }
}
