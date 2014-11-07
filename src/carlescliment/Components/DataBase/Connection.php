<?php

namespace carlescliment\Components\DataBase;

interface Connection
{
    /**
     * @param $query string SQL string with arguments as tokens
     * @param $params array Keyed array of tokens and values
     *
     * @throws carlescliment\Components\DataBase\Exception\DataBaseException
     * @return carlescliment\Components\DataBase\Connection Itself
     */
    public function execute($query, array $params = array());

    /**
     * @return int Last inserted id
     */
    public function lastInsertId();
}
