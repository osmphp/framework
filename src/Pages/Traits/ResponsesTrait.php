<?php

namespace Osm\Framework\Pages\Traits;

use Osm\Core\App;
use Osm\Framework\Http\Responses;
use Osm\Framework\Themes\Module;
use Osm\Framework\Themes\Theme;
use Symfony\Component\HttpFoundation\Response;
use function Osm\__;

/**
 * @property ?Theme $error_page_theme
 */
trait ResponsesTrait
{
    protected function around_notFound(callable $proceed, string $message)
        : Response
    {
        /* @var Responses $this */

        if (!$this->error_page_theme?->views->exists('std-pages::404')) {
            return $proceed($message);
        }

        try {
            return $this->errorView('std-pages::404',
                ['message' => $message], status: 404);
        }
        catch (\Exception $e) {
            $this->log(
                __("Can't render :status page: ",
                    ['status' => 503]) .
                "{$e->getMessage()}\n\n{$e->getTraceAsString()}");

            return $proceed($message);
        }
    }

    protected function around_renderException(callable $proceed,
        ?string $content): Response
    {
        /* @var Responses $this */

        if (!$this->error_page_theme?->views->exists('std-pages::500')) {
            return $proceed($content);
        }

        try {
            return $this->errorView('std-pages::500',
                ['content' => $content], status: 500);
        }
        catch (\Exception $e) {
            $this->log(
                __("Can't render :status page: ",
                    ['status' => 503]) .
                "{$e->getMessage()}\n\n{$e->getTraceAsString()}");

            return $proceed($content);
        }
    }

    protected function around_maintenance(callable $proceed)
        : Response
    {
        /* @var Responses $this */

        if (!$this->error_page_theme?->views->exists('std-pages::503')) {
            return $proceed();
        }

        try {
            return $this->errorView('std-pages::503', status: 503);
        }
        catch (\Exception $e) {
            $this->log(
                __("Can't render :status page: ",
                    ['status' => 503]) .
                "{$e->getMessage()}\n\n{$e->getTraceAsString()}");

            return $proceed();
        }
    }

    protected function get_error_page_theme(): ?Theme {
        global $osm_app; /* @var App $osm_app */
        /* @var Responses $this */

        if ($osm_app->theme) {
            return $osm_app->theme;
        }

        /* @var Module $module */
        $module = $osm_app->modules[Module::class];

        try {
            return $module->themes["_front__{$osm_app->settings->theme}"];
        }
        catch (\Throwable $e) {
            $this->log(__("Can't resolve error page theme: ") .
                "{$e->getMessage()}\n\n{$e->getTraceAsString()}");

            return null;
        }
    }

    protected function errorView(string $template, array $data = [],
        array $mergeData = [], int $status = 200): Response
    {
        global $osm_app; /* @var App $osm_app */
        /* @var Responses $this */

        $theme = $osm_app->theme;
        $osm_app->theme = $this->error_page_theme;

        try {
            return $this->view($template, $data, $mergeData, $status);
        }
        finally {
            $osm_app->theme = $theme;
        }
    }
}