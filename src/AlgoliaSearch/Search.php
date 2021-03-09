<?php

declare(strict_types=1);

namespace Osm\Framework\AlgoliaSearch;

use Osm\Framework\Search\Blueprint as BaseBlueprint;
use Osm\Framework\Search\Query as BaseQuery;
use Osm\Framework\Search\Search as BaseSearch;

class Search extends BaseSearch
{
    public static ?string $name = 'algolia';

    protected function createBlueprint($data): BaseBlueprint {
        return Blueprint::new($data);
    }

    protected function createQuery($data): BaseQuery {
        return Query::new($data);
    }
}