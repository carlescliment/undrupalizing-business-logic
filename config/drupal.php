<?php

$container = new Pimple\Container();

$container['database.connection'] = function($c) {
    $query_wrapper = new carlescliment\Components\DataBase\Adapter\Drupal\DBQueryWrapper();

    return new carlescliment\Components\DataBase\Adapter\Drupal\Connection($query_wrapper);
};

$container['quinieleitor.prizes'] = array(
    'slip_value' => 100,
    'prizes' => array(
        10 => 0.4,
        9 => 0.2,
        8 => 0.12,
        7 => 0.08,
    ),
);

return $container;
