<?php

$container = new Pimple\Container();

$container['database.connection'] = function($c) {
    $query_wrapper = new carlescliment\Components\DataBase\Adapter\Drupal\DBQueryWrapper();

    return new carlescliment\Components\DataBase\Adapter\Drupal\Connection($query_wrapper);
};

return $container;
