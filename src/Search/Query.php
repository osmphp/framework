<?php

declare(strict_types=1);

namespace Osm\Framework\Search;

use Osm\Core\App;
use Osm\Core\BaseModule;
use Osm\Core\Object_;
use Osm\Framework\Search\Filters\And_;
use Osm\Framework\Search\Filters\LogicalFilter;

/**
 * @property Search $search
 * @property string $index_name
 * @property Module $search_module
 * @property LogicalFilter $filter
 */
abstract class Query extends Object_
{
    abstract public function insert(array $data): void;

    public function where(string $fieldName, string $method,
        mixed $value = null): static
    {
        $new = "{$this->search_module->filter_classes[$method]}::new";

        $this->filter->filters[] = $new([
            'field_name' => $fieldName,
            'value' => $value,
        ]);

        return $this;
    }

    abstract public function get(): Result;

    public function value(): ?string {
        return $this->get()->uids[0] ?? null;
    }

    /** @noinspection PhpUnused */
    protected function get_search_module(): BaseModule {
        global $osm_app; /* @var App $osm_app */

        return $osm_app->modules[Module::class];
    }

    /** @noinspection PhpUnused */
    protected function get_filter(): LogicalFilter {
        return And_::new();
    }
}