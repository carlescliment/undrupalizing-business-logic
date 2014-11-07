<?php

namespace carlescliment\Components\DataBase\Adapter\PDO;

use carlescliment\Components\DataBase\Connection as ConnectionInterface;
use carlescliment\Components\DataBase\Exception\DataBaseException;

class Connection implements ConnectionInterface
{
    private $connection;

    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

    public function execute($query, array $params = array())
    {
        $statement = $this->connection->prepare($query);
        $this->prepareParams($statement, $params);
        $success = $statement->execute();
        if (!$success) {
            $error = $statement->errorInfo();
            throw new DataBaseException($error[2] . ' executing ' . $query);
        }

        return new Statement($statement);
    }

    private function prepareParams($statement, array $params = array())
    {
        if (!empty($params))
        {
            foreach ($params as $key => $value)
            {
                $statement->bindValue($key, $value, $this->getParamType($value));
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

    public function lastInsertId()
    {
        return $this->connection->lastInsertId();
    }
}
