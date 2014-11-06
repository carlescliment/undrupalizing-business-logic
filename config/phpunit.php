<?php

$container = require_once 'drupal.php';

$container['database.connection'] = function($c) {
    $connection = new \PDO('mysql:host=localhost;dbname=quinieleitor_test', 'root', '', array());

    return new carlescliment\Components\DataBase\Adapter\PDO\Connection($connection);
};

return $container;
