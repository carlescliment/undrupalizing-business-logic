<?php

namespace spec\carlescliment\Components\DataBase\Adapter\PDO;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ConnectionSpec extends ObjectBehavior
{

    function let(\PDO $connection)
    {
        $this->beConstructedWith($connection);
    }

    function it_should_be_a_connection()
    {
        $this->shouldImplement('carlescliment\Components\DataBase\Connection');
    }

    function it_prepares_a_query_and_executes_statements(\PDO $connection, \PDOStatement $statement)
    {
        // Arrange
        $query = "SELECT * FROM table WHERE id = :int AND name = :string AND image = :null AND active = :bool";
        $params = array(
            ':int' => 1,
            ':string' => "string",
            ':null' => null,
            ':bool' => true,
            ':float' => 2.666,
        );
        $connection->prepare($query)->willReturn($statement);

        // Expect
        $statement->bindValue(':int', $params[':int'], \PDO::PARAM_INT)->shouldBeCalled();
        $statement->bindValue(':string', $params[':string'], \PDO::PARAM_STR)->shouldBeCalled();
        $statement->bindValue(':null', $params[':null'], \PDO::PARAM_NULL)->shouldBeCalled();
        $statement->bindValue(':bool', $params[':bool'], \PDO::PARAM_BOOL)->shouldBeCalled();
        $statement->bindValue(':float', $params[':float'], \PDO::PARAM_STR)->shouldBeCalled();
        $statement->execute()->shouldBeCalled()->willReturn(true);

        // Act
        $this->execute($query, $params);
    }

    function it_can_fetch_all_query_data_at_same_time(\PDO $connection, \PDOStatement $statement)
    {
        // Arrange
        $data = array(array('key' => 'value'), array('other_key' => 'other_value'));
        $statement->execute()->willReturn(true);
        $statement->fetchAll(\PDO::FETCH_ASSOC)->willReturn($data);
        $connection->prepare(Argument::any())->willReturn($statement);

        $this->execute(Argument::any());

        // Act & Assert
        $this->fetchAll()->shouldBeEqualTo($data);
    }

    function it_can_fetch_query_data_one_by_one(\PDO $connection, \PDOStatement $statement)
    {
        // Arrange
        $data = array(array('key' => 'value'), array('other_key' => 'other_value'));
        $statement->execute()->willReturn(true);
        $statement->fetch()->willReturn($data[0], $data[1]);
        $statement->fetch(\PDO::FETCH_ASSOC)->willReturn($data[0], $data[1]);
        $connection->prepare(Argument::any())->willReturn($statement);

        $this->execute(Argument::any());

        // Act & Assert
        $this->fetch()->shouldBeEqualTo($data[0]);
        $this->fetch()->shouldBeEqualTo($data[1]);
    }

    function it_returns_last_inserted_id(\PDO $connection, \PDOStatement $statement)
    {
        // Arrange
        $id = 1234;
        $statement->execute()->willReturn(true);
        $connection->prepare(Argument::any())->willReturn($statement);
        $connection->lastInsertId()->willReturn($id);

        // Act & Assert
        $this->execute(Argument::any());
        $this->lastInsertId()->shouldBeEqualTo($id);
    }

    function it_throws_an_exception_if_an_error_is_found(\PDO $connection, \PDOStatement $statement)
    {
        $query = 'Some failing query';
        $connection->prepare(Argument::any())->willReturn($statement);
        $statement->errorInfo()->willReturn(array(1,1, 'Some error message'));

        $statement->execute(Argument::any())->willReturn(false);

        $this->shouldThrow('carlescliment\Components\DataBase\Exception\DataBaseException')->duringExecute($query);
    }
}
