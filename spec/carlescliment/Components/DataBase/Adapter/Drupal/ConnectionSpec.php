<?php

namespace spec\carlescliment\Components\DataBase\Adapter\Drupal;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use carlescliment\Components\DataBase\Adapter\Drupal\DBQueryWrapper;

class ConnectionSpec extends ObjectBehavior
{
    function let(DBQueryWrapper $db_query_wrapper)
    {
        $this->beConstructedWith($db_query_wrapper);
    }

    function it_replaces_a_table(DBQueryWrapper $db_query_wrapper)
    {
        $query = 'SELECT * FROM `table`';
        $expected_query = 'SELECT * FROM {table}';

        $db_query_wrapper->execute($expected_query, array())->shouldBeCalled();

        $this->execute($query);
    }

    function it_replaces_many_tables_in_the_same_query(DBQueryWrapper $db_query_wrapper)
    {
        $query = 'SELECT * FROM `table1` t1 JOIN `table2` t2 WHERE t1.id=t2.id';
        $expected_query = 'SELECT * FROM {table1} t1 JOIN {table2} t2 WHERE t1.id=t2.id';

        $db_query_wrapper->execute($expected_query, array())->shouldBeCalled();

        $this->execute($query);
    }

    function it_transforms_integer_arguments(DBQueryWrapper $db_query_wrapper)
    {
        $id = 55;
        $query = 'SELECT * FROM `table1` WHERE id=:id';
        $expected_query = 'SELECT * FROM {table1} WHERE id=%d';

        $db_query_wrapper->execute($expected_query, array($id))->shouldBeCalled();

        $this->execute($query, array(':id' => $id));
    }


    function it_transforms_null_arguments(DBQueryWrapper $db_query_wrapper)
    {
        $query = 'SELECT * FROM `table1` WHERE deleted=:deleted';
        $expected_query = 'SELECT * FROM {table1} WHERE deleted=null';

        $db_query_wrapper->execute($expected_query, array())->shouldBeCalled();

        $this->execute($query, array(':deleted' => null));
    }

    function it_transforms_float_arguments(DBQueryWrapper $db_query_wrapper)
    {
        $amount = 55.5;
        $query = 'SELECT * FROM `table1` WHERE amount=:amount';
        $expected_query = 'SELECT * FROM {table1} WHERE amount=%f';

        $db_query_wrapper->execute($expected_query, array($amount))->shouldBeCalled();

        $this->execute($query, array(':amount' => $amount));
    }

    function it_transforms_non_numeric_arguments(DBQueryWrapper $db_query_wrapper)
    {
        $name = "Will Smith";
        $query = 'SELECT * FROM `table1` WHERE name=:name';
        $expected_query = 'SELECT * FROM {table1} WHERE name="%s"';

        $db_query_wrapper->execute($expected_query, array($name))->shouldBeCalled();

        $this->execute($query, array(':name' => $name));
    }

    function it_transforms_all_ocurrencies_of_duplicated_parameters(DBQueryWrapper $db_query_wrapper)
    {
        $amount = 1;
        $name = "Will Smith";
        $query = 'INSERT INTO `table1` (name, amount) VALUES (:name, :amount) ON DUPLICATE KEY UPDATE name=:name';
        $expected_query = 'INSERT INTO {table1} (name, amount) VALUES ("%s", %d) ON DUPLICATE KEY UPDATE name="%s"';

        $db_query_wrapper->execute($expected_query, array($name, $amount, $name))->shouldBeCalled();

        $this->execute($query, array(':name' => $name, ':amount' => $amount));
    }

    function it_fetches_a_single_result(DBQueryWrapper $db_query_wrapper)
    {
        $db_query_wrapper->fetchArray()->shouldBeCalled();

        $this->fetch();
    }

    function it_fetches_all_the_results(DBQueryWrapper $db_query_wrapper)
    {
        $db_query_wrapper->fetchAll()->shouldBeCalled();

        $this->fetchAll();
    }

    function it_brings_the_last_id(DBQueryWrapper $db_query_wrapper)
    {
        $db_query_wrapper->lastInsertId()->shouldBeCalled();

        $this->lastInsertId();
    }
}
