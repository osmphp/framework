<?php

declare(strict_types=1);

namespace Osm\Framework\Data;

use Osm\Core\App;
use Osm\Core\Object_;

/**
 * @property Query $query
 * @property array $request
 */
class RequestParser extends Object_
{
    protected function get_request(): array {
        global $osm_app; /* @var App $osm_app */

        return $osm_app->http->query;
    }

    public function count(): static {
        if (isset($this->request['count'])) {
            $this->query->count();
            unset($this->request['count']);
        }

        return $this;
    }

    public function select(): static {
        if (isset($this->request['select'])) {
            $columnNames = explode(' ', $this->request['select']);
            foreach ($columnNames as $columnName) {
                $this->query->select($columnName);
            }
            unset($this->request['select']);
        }

        return $this;
    }

    public function filters(): static {
        foreach ($this->request as $key => $value) {
            $this->query->whereEquals($key, $value);
        }

        return $this;
    }
}