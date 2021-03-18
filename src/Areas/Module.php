<?php

/** @noinspection PhpUnusedAliasInspection */
declare(strict_types=1);

namespace Osm\Framework\Areas;

use Osm\Core\BaseModule;
use Osm\Framework\Cache\Attributes\Cached;
use function Osm\get_descendant_classes;

/**
 * @property string[] $area_classes #[Cached('area_classes')]
 */
class Module extends BaseModule
{
    public static array $requires = [
        \Osm\Framework\Cache\Module::class,
    ];

    /** @noinspection PhpUnused */
    protected function get_area_classes(): array {
        return get_descendant_classes(Area::class);
    }
}