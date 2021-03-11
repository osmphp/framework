<?php

declare(strict_types=1);

namespace Osm\Framework\AlgoliaSearch;

use Algolia\AlgoliaSearch\SearchClient;
use Osm\Framework\Search\Blueprint as BaseBlueprint;
use Osm\Framework\Search\Query as BaseQuery;
use Osm\Framework\Search\Search as BaseSearch;

/**
 * @property SearchClient $client
 */
class Search extends BaseSearch
{
    public static ?string $name = 'algolia';
    public bool $wait = false;

    protected function createBlueprint($data): BaseBlueprint {
        return Blueprint::new($data);
    }

    protected function createQuery($data): BaseQuery {
        return Query::new($data);
    }

    /** @noinspection PhpUnused */
    protected function get_client(): SearchClient {
        if (isset($this->config['wait'])) {
            $this->wait = $this->config['wait'];
            unset($this->config['wait']);
        }

        return SearchClient::create(
            $this->config['app_id'],
            $this->config['admin_api_key'],
        );
    }
}