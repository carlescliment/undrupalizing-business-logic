<?php

namespace spec\carlescliment\Components\DataBase\Adapter\PDO;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class StatementSpec extends ObjectBehavior
{
    function let(\PDOStatement $statement)
    {
        $this->beConstructedWith($statement);
    }

    function it_fetches_all_data_from_the_statement(\PDOStatement $statement)
    {
        $data = array(array('key' => 'value'), array('other_key' => 'other_value'));

        $statement->fetchAll(\PDO::FETCH_ASSOC)->shouldBeCalled()->willReturn($data);

        $this->fetchAll()->shouldBeEqualTo($data);
    }

    function it_fetches_data_one_by_one(\PDO $connection, \PDOStatement $statement)
    {
        $data = array(array('key' => 'value'), array('other_key' => 'other_value'));

        $statement->fetch(\PDO::FETCH_ASSOC)->willReturn($data[0], $data[1]);

        $this->fetch()->shouldBeEqualTo($data[0]);
        $this->fetch()->shouldBeEqualTo($data[1]);
    }
}
