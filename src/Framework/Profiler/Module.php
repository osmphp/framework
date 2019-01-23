<?php

namespace Manadev\Framework\Profiler;

use Manadev\Core\App;
use Manadev\Core\Modules\BaseModule;
use Manadev\Core\Profiler;
use Manadev\Framework\Profiler\Exceptions\ProfilerError;
use Manadev\Framework\Settings\Settings;
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
        'Manadev_Framework_Settings',
    ];

    protected function default($property) {
        global $m_app; /* @var App $m_app */
        global $m_profiler; /* @var Profiler $m_profiler */

        switch ($property) {
            case 'filename': return $m_app->path(
                "{$m_app->temp_path}/profiler/{$m_profiler->getId()}.ser");
            case 'settings': return $m_app->settings;
            case 'time_to_live': return $this->settings->profiler_time_to_live * 60;
        }
        return parent::default($property);
    }

    public function boot() {
        global $m_profiler; /* @var Profiler $m_profiler */

        parent::boot();

        $this->gc();

        if ($m_profiler) {
            $m_profiler->setTerminator(function() {
                global $m_app; /* @var App $m_app */
                global $m_profiler; /* @var Profiler $m_profiler */

                $m_profiler->record('total', 'total', $m_profiler->elapsed($m_app->started_at));
                file_put_contents(m_make_dir_for($this->filename), serialize($m_profiler->getMetrics()));
            });
        }
    }

    public function gc()
    {
        global $m_profiler; /* @var Profiler $m_profiler */

        if (!$m_profiler) {
            return;
        }

        if (rand(0, 99) >= 2) {
            return;
        }

        if (!$this->time_to_live) {
            throw new ProfilerError(m_("'profiler_time_to_live' setting should be positive number"));
        }

        $files = Finder::create()
            ->in(m_make_dir(dirname($this->filename)))
            ->files()
            ->ignoreDotFiles(true)
            ->date("<= now - {$this->time_to_live} seconds");

        foreach ($files as $file) {
            /* @var SplFileInfo $file */
            unlink($file->getPathname());
        }
    }

}