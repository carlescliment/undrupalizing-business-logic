<?php

namespace carlescliment\Quinieleitor;

class Match
{
    private $id;
    private $name;
    private $result;

    public function __construct($id, $name, $result = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->result = $result;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getResult()
    {
        return $this->result;
    }

}
