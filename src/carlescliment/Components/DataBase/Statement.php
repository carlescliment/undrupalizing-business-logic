<?php

namespace carlescliment\Components\DataBase;

interface Statement
{

    /**
     * @return array The next result
     */
    public function fetch();
    
    /**
     * @return array Array of results
     */
    public function fetchAll();
}

