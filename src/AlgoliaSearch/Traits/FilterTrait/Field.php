<?php

declare(strict_types=1);

namespace Osm\Framework\AlgoliaSearch\Traits\FilterTrait;

use Osm\Core\Exceptions\NotSupported;
use Osm\Framework\AlgoliaSearch\Traits\FilterTrait;
use Osm\Framework\Search\Filter;
use function Osm\__;

trait Field
{
    use FilterTrait;

    /** @noinspection PhpUnused */
    public function toAlgoliaQuery(): string {
        /* @var Filter\Field|Field $this */

        return match($this->operator) {
            '=' => $this->toAlgoliaQuery_equals(),
            'in' => $this->toAlgoliaQuery_in(),
            '>', '<', '>=', '<=' => $this->toAlgoliaQuery_range(
                $this->operator),

            default => throw new NotSupported(__(
                "Algolia search doesn't support ':operator' filter operator",
                ['operator' => $this->operator])),
        };

    }

    protected function toAlgoliaQuery_equals(): string {
        /* @var Filter\Field|Field $this */

        return "{$this->field_name}:{$this->algoliaValue($this->value)}";
    }

    protected function toAlgoliaQuery_in(): string {
        /* @var Filter\Field|Field $this */
        if (count($this->value) == 1) {
            return "{$this->field_name}:{$this->algoliaValue($this->value[0])}";
        }

        $condition = '';
        foreach ($this->value as $value) {
            if ($condition) {
                $condition .= ' OR ';
            }

            $condition .= "{$this->field_name}:{$this->algoliaValue($value)}";
        }

        return "($condition)";
    }

    protected function toAlgoliaQuery_range(string $operator): string {
        /* @var Filter\Field|Field $this */

        return "{$this->field_name} {$operator} {$this->value}";
    }

    protected function algoliaValue(mixed $value): string {
        return is_string($value) ? "'{$value}'" : $value;
    }
}