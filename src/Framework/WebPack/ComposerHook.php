<?php

namespace Osm\Framework\WebPack;

use Osm\Framework\Composer\Hook;
use Osm\Framework\Processes\Process;

class ComposerHook extends Hook
{
    public $events = ['post-create-project', 'post-update'];

    public function run() {
        if (!Process::runBuffered('npm -v')) {
            return;
        }
        if (env('NO_NPM_WEBPACK')) {
            return;
        }

        Process::runInConsoleExpectingSuccess('npm run webpack', true);
    }
}