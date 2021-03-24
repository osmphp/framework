<?php

declare(strict_types=1);

namespace Osm\Framework\Themes;

use Illuminate\View\Compilers\BladeCompiler;
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
use function Osm\make_dir;

/**
 * @property string $name #[Serialized]
 * @property ?string $parent #[Serialized]
 * @property ?bool $dev #[Serialized]
 * @property array $after
 * @property string $gulpfile #[Serialized]
 * @property Factory $views
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

            return new CompilerEngine($compiler, $laravel->files);
        });

        $finder = new FileViewFinder($laravel->files,
            ["{$osm_app->paths->temp}/{$this->name}/views"]);

        $factory = new Factory($resolver, $finder, $laravel->events);
        $factory->setContainer($laravel->container);

        return $factory;
    }
}