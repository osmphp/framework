<?php

declare(strict_types=1);

namespace Osm\Framework\ElasticSearch;

use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use Osm\Core\App;
use Osm\Core\Attributes\Name;
use Osm\Framework\Search\Blueprint as BaseBlueprint;
use Osm\Framework\Search\Query as BaseQuery;
use Osm\Framework\Search\Search as BaseSearch;

/**
 * @property Client $client
 */
#[Name('elastic')]
class Search extends BaseSearch
{
    public bool $refresh = false;

    protected function createBlueprint($data): BaseBlueprint {
        return Blueprint::new($data);
    }

    protected function createQuery($data): BaseQuery {
        return Query::new($data);
    }

    /** @noinspection PhpUnused */
    protected function get_client(): Client {
        global $osm_app; /* @var App $osm_app */

        if (isset($this->config['refresh'])) {
            $this->refresh = $this->config['refresh'];
            unset($this->config['refresh']);
        }

        return ClientBuilder::fromConfig(array_merge([
            'logger' => $osm_app->logs->elastic,
        ], $this->config));
    }
}