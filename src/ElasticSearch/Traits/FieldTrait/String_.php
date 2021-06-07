<?php

declare(strict_types=1);

namespace Osm\Framework\ElasticSearch\Traits\FieldTrait;

use Osm\Framework\ElasticSearch\Traits\FieldTrait;
use Osm\Framework\Search\Field;

trait String_
{
    use FieldTrait;

    /** @noinspection PhpUnused */
    public function generateElasticField(): array {
        /* @var Field\String_ $this */

        if ($this->searchable) {
            return [
                $this->name => [
                    'type' => 'text',
                    'fields' => [
                        'raw' => [
                            'type' => 'keyword',
                        ],
                    ],
                ]
            ];
        }

        return [
            $this->name => [
                'type' => 'keyword',
            ]
        ];
    }
}