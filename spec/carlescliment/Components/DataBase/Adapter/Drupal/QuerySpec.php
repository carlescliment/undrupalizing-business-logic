<?php

namespace spec\carlescliment\Components\DataBase\Adapter\Drupal;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use carlescliment\Components\DataBase\Adapter\Drupal\DBQueryWrapper;

class QuerySpec extends ObjectBehavior
{
    function it_replaces_a_table(DBQueryWrapper $db_query_wrapper)
    {
        $query = 'SELECT * FROM `table`';
        $expected_query = 'SELECT * FROM {table}';
        $this->beConstructedWith($query, array());

        $this->executeWith($db_query_wrapper);

        $db_query_wrapper->execute($expected_query, array())->shouldBeCalled();
    }

    function it_replaces_many_tables_in_the_same_query(DBQueryWrapper $db_query_wrapper)
    {
        $query = 'SELECT * FROM `table1` t1 JOIN `table2` t2 WHERE t1.id=t2.id';
        $expected_query = 'SELECT * FROM {table1} t1 JOIN {table2} t2 WHERE t1.id=t2.id';
        $this->beConstructedWith($query, array());

        $this->executeWith($db_query_wrapper);

        $db_query_wrapper->execute($expected_query, array())->shouldBeCalled();
    }

    function it_transforms_integer_arguments(DBQueryWrapper $db_query_wrapper)
    {
        $id = 55;
        $query = 'SELECT * FROM `table1` WHERE id=:id';
        $expected_query = 'SELECT * FROM {table1} WHERE id=%d';
        $this->beConstructedWith($query, array(':id' => $id));

        $this->executeWith($db_query_wrapper);

        $db_query_wrapper->execute($expected_query, array($id))->shouldBeCalled();
    }

    function it_transforms_null_arguments(DBQueryWrapper $db_query_wrapper)
    {
        $query = 'SELECT * FROM `table1` WHERE deleted=:deleted';
        $expected_query = 'SELECT * FROM {table1} WHERE deleted=null';
        $this->beConstructedWith($query, array(':deleted' => null));

        $this->executeWith($db_query_wrapper);

        $db_query_wrapper->execute($expected_query, array())->shouldBeCalled();
    }

    function it_transforms_float_arguments(DBQueryWrapper $db_query_wrapper)
    {
        $amount = 55.5;
        $query = 'SELECT * FROM `table1` WHERE amount=:amount';
        $expected_query = 'SELECT * FROM {table1} WHERE amount=%f';
        $this->beConstructedWith($query, array(':amount' => $amount));

        $this->executeWith($db_query_wrapper);

        $db_query_wrapper->execute($expected_query, array($amount))->shouldBeCalled();
    }

    function it_transforms_non_numeric_arguments(DBQueryWrapper $db_query_wrapper)
    {
        $name = "Will Smith";
        $query = 'SELECT * FROM `table1` WHERE name=:name';
        $expected_query = 'SELECT * FROM {table1} WHERE name="%s"';
        $this->beConstructedWith($query, array(':name' => $name));

        $this->executeWith($db_query_wrapper);

        $db_query_wrapper->execute($expected_query, array($name))->shouldBeCalled();
    }

    function it_transforms_all_ocurrencies_of_duplicated_parameters(DBQueryWrapper $db_query_wrapper)
    {
        $amount = 1;
        $name = "Will Smith";
        $query = 'INSERT INTO `table1` (name, amount) VALUES (:name, :amount) ON DUPLICATE KEY UPDATE name=:name';
        $expected_query = 'INSERT INTO {table1} (name, amount) VALUES ("%s", %d) ON DUPLICATE KEY UPDATE name="%s"';
        $this->beConstructedWith($query, array(':name' => $name, ':amount' => $amount));

        $this->executeWith($db_query_wrapper);

        $db_query_wrapper->execute($expected_query, array($name, $amount, $name))->shouldBeCalled();
    }
}
