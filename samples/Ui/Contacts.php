<?php

namespace Manadev\Samples\Ui;

use Manadev\Data\TableQueries\TableQuery;
use Manadev\Data\TableSearch\Search;

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