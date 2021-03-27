<?php

declare(strict_types=1);

namespace Osm\Framework\Blade;

use Illuminate\Filesystem\Filesystem;
use Osm\Core\BaseModule;
use Osm\Core\Object_;
use Osm\Framework\Themes\Theme;
use Illuminate\Contracts\View\Factory as FactoryContract;
use Illuminate\View\DynamicComponent;
use Illuminate\View\Engines\CompilerEngine;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\Engines\FileEngine;
use Illuminate\View\Engines\PhpEngine;
use Illuminate\View\Factory;
use Illuminate\View\FileViewFinder;
use Osm\Core\App;
use Osm\Framework\Laravel\Module as Laravel;
use function Osm\make_dir;

/**
 * @property Theme $theme
 * @property Factory $factory
 * @property EngineResolver $resolver
 * @property Compiler $compiler
 * @property FileEngine $file_engine
 * @property PhpEngine $php_engine
 * @property CompilerEngine $blade_engine
 * @property FileViewFinder $finder
 * @property Laravel $laravel
 */
class Provider extends Object_
{
    /** @noinspection PhpUnused */
    protected function get_factory(): Factory {
        $factory = new Factory($this->resolver, $this->finder,
            $this->laravel->events);

        $factory->setContainer($this->laravel->container);
        $this->laravel->container->instance(FactoryContract::class,
            $factory);

        return $factory;
    }

    /** @noinspection PhpUnused */
    protected function get_resolver(): EngineResolver {
        $resolver = new EngineResolver();

        $this->registerEngines($resolver);

        return $resolver;
    }

    /** @noinspection PhpUnused */
    protected function get_files(): Filesystem {
        global $osm_app; /* @var App $osm_app */

        /* @var Laravel $laravel */
        $laravel = $osm_app->modules[Laravel::class];

        return $laravel->files;
    }

    /** @noinspection PhpUnused */
    protected function get_file_engine(): FileEngine {
        return new FileEngine($this->laravel->files);
    }

    /** @noinspection PhpUnused */
    protected function get_php_engine(): PhpEngine {
        return new PhpEngine($this->laravel->files);
    }

    /** @noinspection PhpUnused */
    protected function get_blade_engine(): CompilerEngine {
        return new CompilerEngine($this->compiler, $this->laravel->files);
    }

    /** @noinspection PhpUnused */
    protected function get_compiler(): Compiler {
        global $osm_app; /* @var App $osm_app */

        $compiler = new Compiler($this->laravel->files,
            make_dir("{$osm_app->paths->temp}/view_cache/{$this->theme->name}"));

        $this->registerComponentNamespaces($compiler);
        $this->registerComponents($compiler);

        return $compiler;
    }

    protected function registerComponentNamespaces(Compiler $compiler): void {
        global $osm_app; /* @var App $osm_app */

        foreach ($osm_app->modules as $module) {
            $compiler->componentNamespace(
                "{$module->namespace}\\Components\\" .
                ucfirst($this->theme->area_name), $module->name);
        }
    }

    protected function registerComponents(Compiler $compiler) {
        $compiler->component('dynamic-component',
            DynamicComponent::class);
    }

    protected function registerEngines(EngineResolver $resolver) {
        $resolver->register('file', fn() => $this->file_engine);
        $resolver->register('php', fn() => $this->php_engine);
        $resolver->register('blade', fn() => $this->blade_engine);
    }

    /** @noinspection PhpUnused */
    protected function get_finder(): FileViewFinder {
        global $osm_app; /* @var App $osm_app */

        $finder = new FileViewFinder($this->laravel->files,
            ["{$osm_app->paths->temp}/{$this->theme->name}/views/theme"]);

        foreach ($osm_app->modules as $module) {
            $finder->addNamespace($module->name,
                ["{$osm_app->paths->temp}/{$this->theme->name}/views/{$module->name}"]);
        }

        return $finder;

    }

    /** @noinspection PhpUnused */
    protected function get_laravel(): BaseModule {
        global $osm_app; /* @var App $osm_app */

        return $osm_app->modules[Laravel::class];
    }
}