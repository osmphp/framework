<?php

declare(strict_types=1);

namespace Osm\Framework\ElasticSearch\Traits;

use Osm\Core\Exceptions\NotImplemented;
use Osm\Framework\Search\Field;

/**
 * @property string $elastic_raw_name
 */
trait FieldTrait
{
    public function generateElasticField(): array {
        throw new NotImplemented();
    }

    protected function get_elastic_raw_name(): string {
        /* @var Field $this */
        return $this->searchable
            ? "{$this->name}.raw"
            : $this->name;
    }
}