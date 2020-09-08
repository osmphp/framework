<?php

namespace Osm\Framework\Profiler;

use Osm\Core\App;
use Osm\Core\Modules\BaseModule;
use Osm\Core\Profiler;
use Osm\Framework\Profiler\Exceptions\ProfilerError;
use Osm\Framework\Settings\Settings;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

/**
 * @property string $filename @required
 * @property Settings $settings @required
 * @property int $time_to_live @required @part
 */
class Module extends BaseModule
{
    public $hard_dependencies = [
        'Osm_Framework_Settings',
    ];

    protected function default($property) {
        global $osm_app; /* @var App $osm_app */
        global $osm_profiler; /* @var Profiler $osm_profiler */

        switch ($property) {
            case 'filename': return $osm_app->path(
                "{$osm_app->temp_path}/profiler/{$osm_profiler->getId()}.ser");
            case 'settings': return $osm_app->settings;
            case 'time_to_live': return $this->settings->profiler_time_to_live * 60;
        }
        return parent::default($property);
    }

    public function boot() {
        global $osm_profiler; /* @var Profiler $osm_profiler */

        parent::boot();

        $this->gc();

        if ($osm_profiler) {
            $osm_profiler->setTerminator(function() {
                global $osm_app; /* @var App $osm_app */
                global $osm_profiler; /* @var Profiler $osm_profiler */

                $osm_profiler->record('total', 'total', $osm_profiler->elapsed($osm_app->started_at));
                file_put_contents(osm_make_dir_for($this->filename), serialize($osm_profiler->getMetrics()));
            });
        }
    }

    public function gc()
    {
        global $osm_profiler; /* @var Profiler $osm_profiler */

        if (!$osm_profiler) {
            return;
        }

        if (rand(0, 99) >= 2) {
            return;
        }

        if (!$this->time_to_live) {
            throw new ProfilerError(osm_t("'profiler_time_to_live' setting should be positive number"));
        }

        $files = Finder::create()
            ->in(osm_make_dir(dirname($this->filename)))
            ->files()
            ->ignoreDotFiles(true)
            ->date("<= now - {$this->time_to_live} seconds");

        foreach ($files as $file) {
            /* @var SplFileInfo $file */
            @unlink($file->getPathname());
        }
    }

}