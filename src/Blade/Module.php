<?php

/** @noinspection PhpUnusedAliasInspection */
declare(strict_types=1);

namespace Osm\Framework\Blade;

use Illuminate\View\Component as LaravelComponent;
use Osm\Core\Attributes\Name;
use Osm\Core\BaseModule;
use Osm\Core\Class_;
use Osm\Framework\Blade\Directives\Directive;
use Osm\Framework\Themes\Theme;
use function Osm\get_descendant_classes;
use Osm\Framework\Cache\Attributes\Cached;
use function Osm\get_descendant_classes_by_name;

/**
 * @property array $directive_class_names #[Cached('blade_directive_class_names')]
 * @property array $component_class_names #[Cached('blade_component_class_names')]
 */
class Module extends BaseModule
{
    public static array $requires = [
        \Osm\Framework\Cache\Module::class,
        \Osm\Framework\Themes\Module::class,
    ];

    public static array $traits = [
        Theme::class => Traits\ThemeTrait::class,
    ];

    /** @noinspection PhpUnused */
    protected function get_directive_class_names(): array {
        return array_map(fn(Class_ $class) => $class->name,
            get_descendant_classes(Directive::class));
    }

    /** @noinspection PhpUnused */
    protected function get_component_class_names(): array {
        return get_descendant_classes_by_name(LaravelComponent::class);
    }
}