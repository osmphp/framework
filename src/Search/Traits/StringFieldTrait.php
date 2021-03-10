<?php

declare(strict_types=1);

namespace Osm\Framework\Search\Traits;

trait StringFieldTrait
{
    use FieldTrait;

    /** @noinspection PhpUnused */
    public function generateElasticField(): array {
        return [
            'type' => 'keyword',
        ];
    }
}