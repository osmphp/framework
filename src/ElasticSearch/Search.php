<?php

declare(strict_types=1);

namespace Osm\Framework\ElasticSearch;

use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use Osm\Framework\Search\Blueprint as BaseBlueprint;
use Osm\Framework\Search\Query as BaseQuery;
use Osm\Framework\Search\Search as BaseSearch;

/**
 * @property Client $client
 */
class Search extends BaseSearch
{
    public static ?string $name = 'elastic';

    protected function createBlueprint($data): BaseBlueprint {
        return Blueprint::new($data);
    }

    protected function createQuery($data): BaseQuery {
        return Query::new($data);
    }

    /** @noinspection PhpUnused */
    protected function get_client(): Client {
        return ClientBuilder::fromConfig($this->config);
    }
}