<?php

namespace Osm\Samples\Ui;

use Osm\Data\TableQueries\TableQuery;
use Osm\Data\TableSearch\Search;

class Contacts extends Search
{
    public $sheet = 't_contacts';

    /**
     * @return TableQuery
     */
    protected function createQuery() {
        return $this->db['t_contacts'];
    }
}