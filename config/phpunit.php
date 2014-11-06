<?php

$container = require_once 'drupal.php';

$container['database.connection'] = function($c) {
    global $db_url;
    preg_match_all('/mysqli:\/\/(.*):(.*)@(.*)\/(.*)/',$db_url['default'], $matches);
    $username = $matches[1][0];
    $password = $matches[2][0];
    $host = $matches[3][0];
    $dbname = $matches[4][0];
    $dns = sprintf('mysql:host=%s;dbname=%s', $host, $dbname);
    $connection = new \PDO($dns, $username, $password, array());

    return new carlescliment\Components\DataBase\Adapter\PDO\Connection($connection);
};

return $container;
