<?php

declare(strict_types=1);

namespace Osm\Framework\ElasticSearch\Traits\FieldTrait;

use Osm\Core\Exceptions\NotSupported;
use Osm\Framework\ElasticSearch\Traits\FieldTrait;
use Osm\Framework\Search\Field;
use function Osm\__;

trait Bool_
{
    use FieldTrait;

    /** @noinspection PhpUnused */
    public function generateElasticField(): array {
        /* @var Field\Bool_ $this */
        if ($this->searchable) {
            throw new NotSupported(__(
                "Search fields of ':type' type can't be searchable",
                ['type' => $this->type]));
        }

        return [
            $this->name => [
                'type' => 'boolean',
            ]
        ];
    }
}