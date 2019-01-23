<?php

namespace Manadev\Framework\Views;

use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\Engines\FileEngine;
use Illuminate\View\Engines\PhpEngine;
use Illuminate\View\FileViewFinder;
use Manadev\Core\App;
use Manadev\Core\Modules\BaseModule;
use Illuminate\View\View as LaravelView;

/**
 * @property \Manadev\Framework\Laravel\Module $laravel @required
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
        global $m_app; /* @var App $m_app */

        switch ($property) {
            case 'laravel': return $m_app->modules['Manadev_Framework_Laravel'];
            case 'laravel_view_finder': return $m_app->createRaw(FileViewFinder::class,
                $this->laravel->files,
                [$m_app->path("{$m_app->temp_path}/views/{$m_app->area}/{$m_app->theme}")]);
            case 'laravel_blade_compiler': return $m_app->createRaw(BladeCompiler::class,
                $this->laravel->files, m_make_dir($m_app->path("{$m_app->temp_path}/cache/views")));
            case 'laravel_view_resolver':
                $resolver = $m_app->createRaw(EngineResolver::class);
                $resolver->register('file', function () {
                    global $m_app; /* @var App $m_app */

                    return $m_app->createRaw(FileEngine::class);
                });
                $resolver->register('php', function () {
                    global $m_app; /* @var App $m_app */

                    return $m_app->createRaw(PhpEngine::class);
                });
                $resolver->register('blade', function () {
                    global $m_app; /* @var App $m_app */

                    return $m_app->createRaw(CompilerEngine::class, $this->laravel_blade_compiler);
                });
                return $resolver;
            case 'laravel_view':
                /* @var ViewFactory $viewFactory */
                $viewFactory = $m_app->createRaw(ViewFactory::class, $this->laravel_view_resolver,
                    $this->laravel_view_finder, $this->laravel->events);
                $viewFactory->setContainer($this->laravel->container);
                return $viewFactory;
        }
        return parent::default($property);
    }
}