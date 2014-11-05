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
     * @return array The next result
     */
    public function fetch();
    
    /**
     * @return array Array of results
     */
    public function fetchAll();

    /**
     * @return int Last inserted id
     */
    public function lastInsertId();
}
