<?php

declare(strict_types=1);

namespace Osm\Framework\ElasticSearch\Traits\Filters;

use Osm\Core\Exceptions\NotSupported;
use Osm\Framework\Search\Filters\Field;
use function Osm\__;

trait FieldTrait
{
    use FilterTrait;

    /** @noinspection PhpUnused */
    public function toElasticQuery(): array {
        /* @var Field|FieldTrait $this */

        // TODO

        return match($this->operator) {
            '=' => $this->toElasticQuery_equals(),

            default => throw new NotSupported(__(
                "Elastic search doesn't support ':operator' filter operator",
                ['operator' => $this->operator])),
        };
    }

    protected function toElasticQuery_equals(): array {
        /* @var Field|FieldTrait $this */

        return [
            'term' => [
                $this->field_name => $this->value,
            ],
        ];
    }
}