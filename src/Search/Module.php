<?php

/** @noinspection PhpUnusedAliasInspection */
declare(strict_types=1);

namespace Osm\Framework\Search;

use Osm\Core\App;
use Osm\Core\BaseModule;
use Osm\Framework\Settings\Hints\Settings;
use Osm\Framework\Cache\Attributes\Cached;
use function Osm\get_descendant_classes_by_name;

/**
 * @property string[] $search_classes #[Cached('search_classes')]
 */
class Module extends BaseModule
{
    public static array $requires = [
        \Osm\Framework\Settings\Module::class,
    ];

    public static array $traits = [
        App::class => Traits\AppTrait::class,
        Settings::class => Traits\SettingsTrait::class,
    ];

    /** @noinspection PhpUnused */
    protected function get_search_classes(): array {
        return get_descendant_classes_by_name(Search::class);
    }
}