<?php

namespace Osm\Framework\Testing\Commands;

use Osm\Core\App;
use Osm\Framework\Console\Command;
use Osm\Core\Packages\Package;
use Osm\Framework\Testing\ConfigModule;
use Osm\Framework\Testing\ConfigSuite;

/**
 * @property ConfigSuite[] $suites @temp
 */
class ConfigPhpunit extends Command
{
    public function run() {
        global $osm_app; /* @var App $osm_app */

        $filename = $osm_app->path('phpunit.xml');

        $this->collect();
        $output = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<phpunit backupGlobals="false"
         backupStaticAttributes="false"
         bootstrap="{$osm_app->modules['Osm_Framework_Testing']->path}/bootstrap.php"
         colors="true"
         convertErrorsToExceptions="true"
         convertNoticesToExceptions="true"
         convertWarningsToExceptions="true"
         processIsolation="false"
         stopOnFailure="true">
    <php>
        <env name="APP_ENV" value="testing"/>
{$this->renderNoFresh()}
{$this->renderNoMigrate()}
{$this->renderNoWebpack()}
    </php>  
    <testsuites>
{$this->renderSuites()}
    </testsuites>
    <filter>
        <whitelist>
{$this->renderCoveredPackages()}
        </whitelist>
    </filter>
</phpunit>
EOT;
        file_put_contents($filename, $output);
        @chmod($filename, $osm_app->readonly_file_permissions);
    }

    protected function collect() {
        global $osm_app; /* @var App $osm_app */

        $this->suites = [];
        foreach ($osm_app->packages as $package) {
            $this->collectFromPackage($package);
        }

        if (!($suites = $this->input->getArgument('suite'))) {
            return;
        }

        foreach (array_keys($this->suites) as $suite) {
            if (!in_array($suite, $suites)) {
                unset($this->suites[$suite]);
            }
        }
    }

    protected function collectFromPackage(Package $package) {
        global $osm_app; /* @var App $osm_app */

        if (!isset($package->namespaces['tests'])) {
            return;
        }
        $path = $osm_app->path("{$package->path}/tests");
        if (!is_dir($path)) {
            return;
        }

        $this->collectFromPath($package, $path);
    }

    protected function collectFromPath(Package $package, $basePath, $path = '') {
        global $osm_app; /* @var App $osm_app */

        foreach (new \DirectoryIterator($basePath . ($path ? "/$path" : '')) as $fileInfo) {
            if ($fileInfo->isDot()) {
                continue;
            }

            $name = ($path ? "$path/" : '') . $fileInfo->getFilename();
            if ($fileInfo->isDir()) {
                $this->collectFromPath($package, $basePath, $name);
                continue;
            }

            if ($osm_app->ignore($fileInfo->getPathname())) {
                continue;
            }

            if ($fileInfo->getExtension() != 'php') {
                continue;
            }

            $class = $package->namespaces['tests'] . '\\' .
                str_replace('/', '\\',
                    ($path ? "$path/" : '') . pathinfo($fileInfo->getFilename(), PATHINFO_FILENAME));

            if (strrpos($class, 'Test') != strlen($class) - strlen('Test')) {
                continue;
            }
            $test = new $class();

            $suite = $test->suite ?? '';
            if (!isset($this->suites[$suite])) {
                $this->suites[$suite] = ConfigSuite::new(['modules' => []], $suite);
            }
            $_suite = $this->suites[$suite];

            $module = $this->getModule($package, $class);
            if (!isset($_suite->modules[$module])) {
                $_suite->modules[$module] = ConfigModule::new(['files' => []], $module);
            }
            $_module = $_suite->modules[$module];

            $_module->files[] = strtr(substr($fileInfo->getPathname(), strlen($osm_app->base_path) + 1), '\\', '/');
        }

    }

    /**
     * @param Package $package
     * @param string $class
     * @return string
     */
    protected function getModule($package, $class) {
        global $osm_app; /* @var App $osm_app */

        if (!isset($package->namespaces['src'])) {
            return '';
        }

        $class = $package->namespaces['src'] . substr($class, strlen($package->namespaces['tests']));
        foreach ($osm_app->modules as $module) {
            if (strpos($class, str_replace('_', '\\', $module->name)) === 0) {
                return $module->name;
            }
        }

        return '';
    }

    protected function renderSuites() {
        global $osm_app; /* @var App $osm_app */

        $result = '';
        foreach ($osm_app->testing->suites as $suite) {
            if (isset($this->suites[$suite->name])) {
                $result .= $this->renderModules($this->suites[$suite->name]);
            }
        }
        if (isset($this->suites[''])) {
            $result .= $this->renderModules($this->suites['']);
        }

        return $result;
    }

    protected function renderModules(ConfigSuite $suite) {
        global $osm_app; /* @var App $osm_app */

        $title = $osm_app->testing->suites[$suite->name]->title ?? m_('Other Tests');
        $result = "        <testsuite name=\"{$title}\">\n";

        if (isset($suite->modules[''])) {
            $result .= $this->renderFiles($suite->modules['']);
        }

        foreach ($osm_app->modules as $module) {
            if (isset($suite->modules[$module->name])) {
                $result .= $this->renderFiles($suite->modules[$module->name]);
            }
        }

        $result .= "        </testsuite>\n";

        return $result;
    }

    protected function renderFiles(ConfigModule $module) {
        $result = '';
        foreach ($module->files as $file) {
            $result .= "            <file>{$file}</file>\n";
        }
        return $result;
    }

    protected function renderNoFresh() {
        return $this->input->getOption('no-fresh')
            ? '        <env name="NO_FRESH" value="1"/>'
            : '';
    }

    protected function renderNoMigrate() {
        return $this->input->getOption('no-migrate')
            ? '        <env name="NO_MIGRATE" value="1"/>'
            : '';
    }

    protected function renderNoWebpack() {
        return $this->input->getOption('no-webpack')
            ? '        <env name="NO_WEBPACK" value="1"/>'
            : '';
    }

    protected function renderCoveredPackages() {
        global $osm_app; /* @var App $osm_app */

        $result = '';
        foreach ($osm_app->packages as $package) {
            $result .= "            <directory suffix=\".php\">{$package->path}/src</directory>\n";
        }
        return $result;
    }
}