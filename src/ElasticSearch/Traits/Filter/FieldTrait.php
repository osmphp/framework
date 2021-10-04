<?php

declare(strict_types=1);

namespace Osm\Framework\ElasticSearch\Traits\Filter;

use Osm\Core\Attributes\UseIn;
use Osm\Core\Exceptions\NotSupported;
use Osm\Framework\ElasticSearch\Traits\FilterTrait;
use Osm\Framework\Search\Filter\Field;
use function Osm\__;

#[UseIn(Field::class)]
trait FieldTrait
{
    use FilterTrait;

    /** @noinspection PhpUnused */
    public function toElasticQuery(bool $root = false): array {
        /* @var Field|FieldTrait $this */

        return match($this->operator) {
            '=' => $this->toElasticQuery_equals(),
            'in' => $this->toElasticQuery_in(),
            '>', '<', '>=', '<=' => $this->toElasticQuery_range(
                $this->operator),

            default => throw new NotSupported(__(
                "Elastic search doesn't support ':operator' filter operator",
                ['operator' => $this->operator])),
        };
    }

    protected function toElasticQuery_equals(): array {
        /* @var Field|FieldTrait $this */

        return [
            'term' => [
                $this->field->elastic_raw_name => $this->value,
            ],
        ];
    }

    protected function toElasticQuery_in(): array {
        /* @var Field|FieldTrait $this */

        return [
            'terms' => [
                $this->field->elastic_raw_name => $this->value,
            ],
        ];
    }


    protected function toElasticQuery_range(string $operator): array {
        /* @var Field|FieldTrait $this */

        static $operators = [
            '>' => 'gt',
            '<' => 'lt',
            '>=' => 'gte',
            '<=' => 'lte',
        ];

        $operator = $operators[$operator];

        return [
            'range' => [
                $this->field->elastic_raw_name => [
                    $operator => $this->value,
                ],
            ],
        ];
    }
}