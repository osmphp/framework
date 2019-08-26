<?php

namespace Osm\Framework\Views;

use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\Engines\FileEngine;
use Illuminate\View\Engines\PhpEngine;
use Illuminate\View\FileViewFinder;
use Osm\Core\App;
use Osm\Core\Modules\BaseModule;
use Illuminate\View\View as LaravelView;

/**
 * @property \Osm\Framework\Laravel\Module $laravel @required
 * @property EngineResolver $laravel_view_resolver @required
 * @property FileViewFinder $laravel_view_finder @required
 * @property ViewFactory $laravel_view @required
 * @property BladeCompiler $laravel_blade_compiler @required
 */
class Module extends BaseModule
{
    public $traits = [
        LaravelView::class => Traits\LaravelViewTrait::class,
    ];

    protected function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'laravel': return $osm_app->modules['Osm_Framework_Laravel'];
            case 'laravel_view_finder': return $osm_app->createRaw(FileViewFinder::class,
                $this->laravel->files,
                [$osm_app->path("{$osm_app->temp_path}/views/{$osm_app->area}/{$osm_app->theme}")]);
            case 'laravel_blade_compiler': return $osm_app->createRaw(BladeCompiler::class,
                $this->laravel->files, osm_make_dir($osm_app->path("{$osm_app->temp_path}/cache/views")));
            case 'laravel_view_resolver':
                $resolver = $osm_app->createRaw(EngineResolver::class);
                $resolver->register('file', function () {
                    global $osm_app; /* @var App $osm_app */

                    return $osm_app->createRaw(FileEngine::class);
                });
                $resolver->register('php', function () {
                    global $osm_app; /* @var App $osm_app */

                    return $osm_app->createRaw(PhpEngine::class);
                });
                $resolver->register('blade', function () {
                    global $osm_app; /* @var App $osm_app */

                    return $osm_app->createRaw(CompilerEngine::class, $this->laravel_blade_compiler);
                });
                return $resolver;
            case 'laravel_view':
                /* @var ViewFactory $viewFactory */
                $viewFactory = $osm_app->createRaw(ViewFactory::class, $this->laravel_view_resolver,
                    $this->laravel_view_finder, $this->laravel->events);
                $viewFactory->setContainer($this->laravel->container);
                return $viewFactory;
        }
        return parent::default($property);
    }
}