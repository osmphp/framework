<?php

declare(strict_types=1);

namespace Osm\Framework\Themes;

use Illuminate\Contracts\View\Factory as FactoryContract;
use Illuminate\View\DynamicComponent;
use Illuminate\View\Engines\CompilerEngine;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\Engines\FileEngine;
use Illuminate\View\Engines\PhpEngine;
use Illuminate\View\Factory;
use Illuminate\View\FileViewFinder;
use Osm\Core\App;
use Osm\Core\Object_;
use Osm\Core\Attributes\Serialized;
use Osm\Framework\Laravel\Module as Laravel;
use Osm\Framework\Themes\Blade\BladeCompiler;
use function Osm\make_dir;

/**
 * @property string $name #[Serialized]
 * @property ?string $parent #[Serialized]
 * @property ?bool $dev #[Serialized]
 * @property array $after
 * @property string $gulpfile #[Serialized]
 * @property Factory $views
 * @property ?string $area_name
 */
class Theme extends Object_
{
    /**
     * @var string[]
     */
    #[Serialized]
    public array $paths = [];

    /** @noinspection PhpUnused */
    protected function get_after(): array {
        return $this->parent ? [$this->parent] : [];
    }

    public function toJson(): \stdClass {
        $json = new \stdClass();

        foreach ($this->__class->properties as $property) {
            if (!isset($property->attributes[Serialized::class])) {
                continue;
            }

            if (($value = $this->{$property->name}) === null) {
                continue;
            }

            $json->{$property->name} = $value;
        }

        return $json;
    }

    /** @noinspection PhpUnused */
    protected function get_views(): Factory {
        global $osm_app; /* @var App $osm_app */

        /* @var Laravel $laravel */
        $laravel = $osm_app->modules[Laravel::class];

        $resolver = new EngineResolver();
        $resolver->register('file', function () {
            global $osm_app; /* @var App $osm_app */

            /* @var Laravel $laravel */
            $laravel = $osm_app->modules[Laravel::class];

            return new FileEngine($laravel->files);
        });
        $resolver->register('php', function () {
            global $osm_app; /* @var App $osm_app */

            /* @var Laravel $laravel */
            $laravel = $osm_app->modules[Laravel::class];

            return new PhpEngine($laravel->files);
        });
        $resolver->register('blade', function () {
            global $osm_app; /* @var App $osm_app */

            /* @var Laravel $laravel */
            $laravel = $osm_app->modules[Laravel::class];

            $compiler = new BladeCompiler($laravel->files,
                make_dir("{$osm_app->paths->temp}/view_cache/{$this->name}"));

            foreach ($osm_app->modules as $module) {
                $compiler->componentNamespace(
                    "{$module->namespace}\\Components\\" .
                    ucfirst($this->area_name), $module->name);
            }

            $compiler->component('dynamic-component',
                DynamicComponent::class);

            return new CompilerEngine($compiler, $laravel->files);
        });

        $finder = new FileViewFinder($laravel->files,
            ["{$osm_app->paths->temp}/{$this->name}/views/theme"]);
        foreach ($osm_app->modules as $module) {
            $finder->addNamespace($module->name,
                ["{$osm_app->paths->temp}/{$this->name}/views/{$module->name}"]);
        }

        $factory = new Factory($resolver, $finder, $laravel->events);
        $factory->setContainer($laravel->container);

        $laravel->container->instance(FactoryContract::class, $factory);

        return $factory;
    }

    /** @noinspection PhpUnused */
    protected function get_area_name(): ?string {
        return preg_match('/^_([^_]+)/', $this->name, $match)
            ? $match[1]: null;
    }
}