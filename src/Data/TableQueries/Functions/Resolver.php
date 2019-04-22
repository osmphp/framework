<?php

namespace Manadev\Data\TableQueries\Functions;

use Manadev\Core\App;
use Manadev\Data\Queries\Functions\Resolver as BaseFunctionResolver;

class Resolver extends BaseFunctionResolver
{
    protected function default($property) {
        global $m_app; /* @var App $m_app */

        switch ($property) {
            case 'functions': return $m_app->table_functions;
        }
        return parent::default($property);
    }
}