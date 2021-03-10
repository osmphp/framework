<?php

declare(strict_types=1);

namespace Osm\Framework\Search\Traits;

trait IntFieldTrait
{
    use FieldTrait;

    /** @noinspection PhpUnused */
    public function generateElasticField(): array {
        return [
            'type' => 'integer',
        ];
    }
}