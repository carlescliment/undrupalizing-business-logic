<?php

namespace carlescliment\Quinieleitor;

use carlescliment\Quinieleitor\Controller\ApplicationController;
use carlescliment\Quinieleitor\ResultsSlip,
    carlescliment\Quinieleitor\Match,
    carlescliment\Quinieleitor\Score\Score;

class BetterTest extends \PHPUnit_Framework_TestCase
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

    function testBetForCurrentBettingSlip() {
        // Arrange
        $uid = 5;
        $current_slip = $this->helper->createBettingSlipForDate(date('Y-m-d', strtotime('+1 day')));
        $better_slip = new BetterSlip($uid);
        foreach ($current_slip->getMatches() as $match) {
            $better_slip->add(new Bet(null, $match->getId(), '1'));
        }

        // Act
        $this->controller->saveBet($better_slip);

        // Assert
        $player_slip = $this->helper->loadBetterSlip($uid, $current_slip->getId());
        $this->assertEquals(ResultsSlip::MATCHES_PER_SLIP, count($player_slip->getBets()));
        foreach ($player_slip->getBets() as $bet) {
            $this->assertEquals(1, $bet->getPrediction());
        }
    }

    function testSeeTheCurrentHallOfFame() {
        // Arrange
        for ($uid=1; $uid<=3; $uid++) {
            $score = new Score($uid, 100*$uid);
            $score->save($this->helper->getScoreRepository());
        }

        // Act
        $hall_of_fame = $this->controller->getHallOfFame(5);

        // Assert
        $this->assertCount(3, $hall_of_fame);
        $this->assertEquals(3, $hall_of_fame[0]->getUserId());
        $this->assertEquals(300, $hall_of_fame[0]->getPoints());
        $this->assertEquals(2, $hall_of_fame[1]->getUserId());
        $this->assertEquals(200, $hall_of_fame[1]->getPoints());
        $this->assertEquals(1, $hall_of_fame[2]->getUserId());
        $this->assertEquals(100, $hall_of_fame[2]->getPoints());
    }
}
