<?php

namespace carlescliment\Components\DataBase\Adapter\Drupal;

use carlescliment\Components\DataBase\Exception\DataBaseException;

class DBQueryWrapper
{
    private $result;

    public function execute($query, array $parameters = array())
    {
        $db_query_parameters = array_merge(array($query), $parameters);
        $this->result = call_user_func_array('db_query', $db_query_parameters);
        $error_code = db_error();

        if ($error_code) {
            throw new DataBaseException(sprintf('ERROR CODE %d: %s', $error_code, $query));
        }

        return $this;
    }

    public function fetchArray()
    {
        return db_fetch_array($this->result);
    }

    public function fetchAll()
    {
        $rows = array();
        while ($row = $this->fetchArray()) {
            $rows[] = $row;
        }

        return $rows;
    }

    public function lastInsertId()
    {
        $result = db_query('SELECT LAST_INSERT_ID() as id');

        return db_fetch_object($result)->id;
    }
}
