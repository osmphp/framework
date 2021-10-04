<?php

declare(strict_types=1);

namespace Osm\Framework\ElasticSearch\Traits\Field;


use Osm\Core\Attributes\UseIn;
use Osm\Framework\ElasticSearch\Traits\FieldTrait;
use Osm\Framework\Search\Field\String_;

#[UseIn(String_::class)]
trait StringTrait
{
    use FieldTrait;

    /** @noinspection PhpUnused */
    public function generateElasticField(): array {
        /* @var String_|StringTrait $this */

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