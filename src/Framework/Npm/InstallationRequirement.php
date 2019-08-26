<?php

namespace Osm\Framework\Npm;

use Osm\Core\App;
use Osm\Framework\Installer\Requirement;
use Osm\Framework\Processes\Process;

/**
 * @property string $app_title @required
 */
class InstallationRequirement extends Requirement
{
    public function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'app_title': return $osm_app->settings->app_title;
        }
        return parent::default($property);
    }

    public function check() {
        if (env('NO_NPM_WEBPACK')) {
            return true;
        }

        if (!Process::runBuffered('npm -v')) {
            if ($this->yes != $this->output->choice(m_(
                "Node and NPM are not installed. Though it is possible to use :name in production " .
                "without Node and NPM (though there are benefits in using them in production too), " .
                "it is highly recommended to install and use Node and NPM during development. " .
                "If you continue without installing Node and NPM, you will not be able to publish " .
                "JS, CSS and other assets, you will not be able to configure assets to be " .
                "automatically republished after source files change, you will not be able to configure " .
                "cache to be automatically cleared after source files change. Are you sure you want to continue?",
                ['name' => $this->app_title]), [$this->no, $this->yes], $this->no))
            {
                return false;
            }

            Process::runInConsoleExpectingSuccess("php run env NO_NPM_WEBPACK=true", true);
            putenv("NO_NPM_WEBPACK=true");
        }

        return true;
    }
}