<?php

/** @noinspection PhpUnusedAliasInspection */
declare(strict_types=1);

namespace Osm\Framework\Data;

use Osm\Core\App;
use Osm\Core\BaseModule;
use Osm\Framework\Cache\Attributes\Cached;
use function Osm\get_descendant_classes_by_name;

/**
 * @property array $query_classes #[Cached('data_query_classes')]
 * @property array $type_classes #[Cached('data_type_classes')]
 */
class Module extends BaseModule
{
    public static array $requires = [
        \Osm\Framework\Cache\Module::class,
        \Osm\Framework\Db\Module::class,
        \Osm\Framework\Search\Module::class,
    ];

    public static array $traits = [
        App::class => Traits\AppTrait::class,
    ];

    /** @noinspection PhpUnused */
    protected function get_query_classes(): array {
        return get_descendant_classes_by_name(Query::class);
    }

    /** @noinspection PhpUnused */
    protected function get_type_classes(): array {
        return get_descendant_classes_by_name(Type::class);
    }
}