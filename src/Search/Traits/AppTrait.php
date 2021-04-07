<?php

declare(strict_types=1);

namespace Osm\Framework\Search\Traits;

use Osm\Core\App;
use Osm\Framework\Search\Module;
use Osm\Framework\Search\Search;

/**
 * @property Search $search
 */
trait AppTrait
{
    /** @noinspection PhpUnused */
    protected function get_search(): Search {
        /* @var App $this */

        $drivers = $this->descendants->byName(Search::class);
        $config = $this->settings->search;
        $new = "{$drivers[$config['driver']]}::new";
        unset($config['driver']);


        if (isset($config['index_prefix'])) {
            $data = ['index_prefix' => $config['index_prefix']];
            unset($config['index_prefix']);
        }
        else {
            $data = [];
        }

        $data['config'] = $config;
        return $new($data);
    }
}