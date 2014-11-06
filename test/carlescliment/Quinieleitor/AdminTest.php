<?php

namespace carlescliment\Quinieleitor;

use carlescliment\Quinieleitor\Controller\ApplicationController;
use carlescliment\Quinieleitor\ResultsSlip,
    carlescliment\Quinieleitor\Match;

class AdminTest extends \PHPUnit_Framework_TestCase
{
    private $controller;
    private $helper;

    public function setUp()
    {
        $container = require __DIR__ . '/../../../config/phpunit.php';
        $this->controller = new ApplicationController($container);
        $this->helper = new Helper($container);
        $this->truncateTables($container['database.connection'], array('bets', 'matches', 'betting_slips', 'better_points'));
    }

    private function truncateTables($connection, $tables)
    {
        $connection->execute('SET foreign_key_checks = 0');
        foreach ($tables as $table) {
            $connection->execute(sprintf('TRUNCATE TABLE %s', $table));
        }
        $connection->execute('SET foreign_key_checks = 1');
    }

    function testCreateBettingSlip() {
        // Arrange
        $results_slip = new ResultsSlip(null, new \DateTime());
        $results_slip->add(new Match(null, 'SEV-VAL', null));
        $results_slip->add(new Match(null, 'ALM-ESP', null));
        $results_slip->add(new Match(null, 'CEL-GET', null));
        $results_slip->add(new Match(null, 'RMA-COR', null));
        $results_slip->add(new Match(null, 'EIB-RSO', null));
        $results_slip->add(new Match(null, 'RAY-ATM', null));
        $results_slip->add(new Match(null, 'GRA-DEP', null));
        $results_slip->add(new Match(null, 'FCB-ELC', null));
        $results_slip->add(new Match(null, 'LEV-VIL', null));
        $results_slip->add(new Match(null, 'MAL-ATH', null));

        // Act
        $this->controller->saveSlip($results_slip);

        // Assert
        $loaded_slip = $this->controller->loadSlip($results_slip->getId());
        $matches = $loaded_slip->getMatches();
        $this->assertEquals($matches[0]->getName(), 'SEV-VAL');
        $this->assertEquals($matches[1]->getName(), 'ALM-ESP');
        $this->assertEquals($matches[2]->getName(), 'CEL-GET');
        $this->assertEquals($matches[3]->getName(), 'RMA-COR');
        $this->assertEquals($matches[4]->getName(), 'EIB-RSO');
        $this->assertEquals($matches[5]->getName(), 'RAY-ATM');
        $this->assertEquals($matches[6]->getName(), 'GRA-DEP');
        $this->assertEquals($matches[7]->getName(), 'FCB-ELC');
        $this->assertEquals($matches[8]->getName(), 'LEV-VIL');
        $this->assertEquals($matches[9]->getName(), 'MAL-ATH');
    }


    function testResolveBettingSlips() {
        // Arrange
        $current_slip = $this->helper->createBettingSlipForDate(date('Y-m-d', strtotime('+1 day')));
        $results = array();
        foreach ($current_slip->getMatches() as $match) {
            $results[$match->getId()] = '1';
        }

        // Act
        $this->controller->resolve($current_slip->getId(), $results);

        // Assert
        $saved_slip = $this->controller->loadSlip($current_slip->getId());
        $this->assertTrue($saved_slip->isClosed());
        $this->assertEquals(ResultsSlip::MATCHES_PER_SLIP, count($saved_slip->getMatches()));
        foreach ($saved_slip->getMatches() as $match) {
            $this->assertEquals(1, $match->getResult());
        }
    }


    function testTheClassificationIsUpdatedWhenABettingSlipIsResolved() {
        // Arrange
        $first_better_id = 1;
        $second_better_id = 2;
        $current_slip = $this->helper->createBettingSlipForDate(date('Y-m-d', strtotime('+1 day')));
        $this->helper->betterBetsForSlip($first_better_id, $current_slip, array_fill(0, ResultsSlip::MATCHES_PER_SLIP, '2')); 
        $this->helper->betterBetsForSlip($second_better_id, $current_slip, array_fill(0, ResultsSlip::MATCHES_PER_SLIP, '2')); 
        $results = array_fill(0, ResultsSlip::MATCHES_PER_SLIP, '2');

        // Act
        $this->controller->resolve($current_slip->getId(), $results);

        // Assert
        $hall_of_fame = $this->controller->getHallOfFame(10);
        $this->assertEquals(2, count($hall_of_fame));
    }

}
