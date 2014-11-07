<?php

$container = new Pimple\Container();

$container['database.connection'] = function($c) {
    return new carlescliment\Components\DataBase\Adapter\Drupal\Connection();
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

$container['event.dispatcher'] = function($c) {
    $event_dispatcher_symfony = new Symfony\Component\EventDispatcher\EventDispatcher();
    $event_dispatcher = new carlescliment\Components\EventDispatcher\Adapter\Symfony\SymfonyEventDispatcher($event_dispatcher_symfony);

    $better_slip_repository = new carlescliment\Quinieleitor\Repository\BetterSlipRepository($c['database.connection']);
    $score_repository = new carlescliment\Quinieleitor\Repository\ScoreRepository($c['database.connection']);
    $calculator = new carlescliment\Quinieleitor\Score\PrizeCalculator($c['quinieleitor.prizes']);;
    $updater = new carlescliment\Quinieleitor\Score\Updater($score_repository, $calculator);
    $score_listener = new carlescliment\Quinieleitor\EventDispatcher\Listener\ScoreListener(
        $better_slip_repository, $updater);

    $event_dispatcher->register(carlescliment\Quinieleitor\EventDispatcher\Event\QuinieleitorEvents::SLIP_RESOLVED, $score_listener, 'onSlipResolved');

    return $event_dispatcher;
};

return $container;
