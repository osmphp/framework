<?php

namespace Osm\Core;

use Illuminate\Support\Str;
use Osm\Data\Formulas\Functions\Function_;
use Osm\Data\Formulas\Functions\Functions;
use Osm\Data\OptionLists\OptionList;
use Osm\Data\OptionLists\OptionLists;
use Osm\Data\Sheets\Sheet;
use Osm\Data\Sheets\Sheets;
use Osm\Data\TableQueries\TableQuery;
use Osm\Framework\Areas\Area;
use Osm\Framework\Areas\Areas;
use Osm\Framework\Cache\Cache;
use Osm\Framework\Cache\Caches;
use Osm\Core\Classes\Classes;
use Osm\Core\Compilation\Compiler;
use Osm\Core\Config\ConfigLoader;
use Osm\Core\Environment\EnvironmentLoader;
use Osm\Core\ErrorHandling\ErrorHandler;
use Osm\Core\Exceptions\FactoryError;
use Osm\Core\Modules\BaseModule;
use Osm\Core\Modules\ModuleLoader;
use Osm\Core\Packages\Package;
use Osm\Core\Packages\PackageLoader;
use Osm\Framework\Cron\Job;
use Osm\Framework\Cron\Jobs;
use Osm\Framework\Db\Databases;
use Osm\Framework\Db\Db;
use Osm\Framework\Encryption\Hashing\Hashing;
use Osm\Framework\Http\Controller;
use Osm\Framework\Http\Query as HttpQuery;
use Osm\Framework\Http\Request;
use Osm\Framework\Http\Url;
use Osm\Framework\Layers\Layout;
use Osm\Framework\Logging\Logs;
use Osm\Framework\Queues\Queue;
use Osm\Framework\Queues\Queues;
use Osm\Framework\Sessions\Stores;
use Osm\Framework\Sessions\Stores\Store;
use Osm\Framework\Settings\Settings;
use Osm\Framework\Themes\Theme;
use Osm\Framework\Themes\Themes;
use Symfony\Component\Finder\Glob;
use Symfony\Component\HttpFoundation\Response;

/**
 * Application paths:
 *
 * @property string $base_path @required
 * @property string $config_path @required @part
 * @property string $environment_path @required @part
 * @property string $data_path @required @part
 * @property string $temp_path @required @part
 * @property string $public_path @required @part
 *
 * File and directory permissions:
 *
 * @property int $umask @required @part
 * @property int $readonly_directory_permissions @required @part
 * @property int $readonly_file_permissions @required @part
 * @property int $writable_directory_permissions @required @part
 * @property int $writable_file_permissions @required @part
 *
 * Application parameters:
 *
 * @property string $env Requested environment @required @part
 * @property string $main_module @required
 * @property bool $catch_output
 *
 * Application result:
 *
 * @property Response $response
 * @property int $exit_code
 *
 * Application variables:
 *
 * @property string[] $component_ignore @required @part
 * @property Package[] $packages @required @part
 * @property BaseModule[] $modules @required @part
 * @property string[] $class_names @required @part
 * @property float $started_at @required
 * @property \Throwable $pending_exception
 * @property ErrorHandler $error_handler @required
 *
 * Properties introduced in modules:
 *
 * @see \Osm\Framework\Cache\Module:
 *      @property Caches|Cache[] $caches @required @default
 *      @property Cache $cache Main cache @required @default
 * @see \Osm\Framework\Settings\Module:
 *      @property Settings $settings @required @default
 * @see \Osm\Framework\Db\Module:
 *      @property Databases|Db[] $databases @required @default
 *      @property Db|TableQuery[] $db @required @default
 * @see \Osm\Framework\Http\Module:
 *      @property Request $request @required @default
 *      @property Url $url @required @default
 *      @property Controller $controller @required
 *      @property HttpQuery|array $query @required @default
 * @see \Osm\Framework\Areas\Module:
 *      @property string $area @required
 *      @property Areas|Area[] $areas @required @default
 *      @property Area $area_ @required @default
 * @see \Osm\Framework\Themes\Module:
 *      @property string $theme @required @default
 *      @property Themes $themes @required @default
 *      @property Theme $theme_ @required @default
 * @see \Osm\Framework\Sessions\Module:
 *      @property Stores|Store[] $session_stores @required @default
 * @see \Osm\Framework\Layers\Module:
 *      @property Layout $layout @required
 * @see \Osm\Framework\Queues\Module:
 *      @property Queues|Queue[] $queues @required @default
 * @see \Osm\Framework\Cron\Module:
 *      @property Jobs|Job[] $cron_jobs @required @default
 * @see \Osm\Framework\Cron\Module:
 *      @property Hashing $hashing @required @default
 * @see \Osm\Framework\Logging\Module:
 *      @property Logs $logs @required @default
 * @see \Osm\Data\OptionLists\Module:
 *      @property OptionLists|OptionList[] $option_lists @required @default
 * @see \Osm\Data\TableQueries\Module:
 *      @property Functions|Function_[] $table_functions @required @default
 * @see \Osm\Data\Sheets\Module:
 *      @property Sheets|Sheet[] $sheets @required @default
 *
 * Module shortcuts:
 *
 * @property \Osm\Framework\Laravel\Module $laravel @required
 * @property \Osm\Framework\Localization\Module $localization @required
 * @property \Osm\Framework\Testing\Module $testing @required
 * @property \Osm\Framework\Console\Module $console @required
 * @property \Osm\Framework\Http\Module $http @required
 */
class App extends Object_
{
    public $singletons = [];

    /**
     * @var Properties
     */
    public $properties = null;

    public static function createApp($data = []) {
        global $osm_app; /* @var App $osm_app */
        global $osm_classes; /* @var Classes $osm_classes */
        global $osm_profiler; /* @var Profiler $osm_profiler */

        $osm_classes = new Classes();

        $data['started_at'] = microtime(true);

        $osm_app = $app = new static($data);

        umask($app->umask);
        (new EnvironmentLoader())->load();
        $osm_profiler = env('PROFILE', false) ? new Profiler() : null;
        $elapsed = 0.0;
        $app->error_handler = new ErrorHandler();

        $filename = $app->path("{$app->temp_path}/cache/app.ser");
        $classFilename = $app->path("{$app->temp_path}/cache/classes.json");
        $versionFilename = $app->path("{$app->temp_path}/cache/version.txt");
        $componentIgnoreFilename = $app->path(".componentignore");

        if (file_exists($classFilename)) {
            if (!@filesize($classFilename)) {
                @unlink($classFilename);
            }
            else {
                if ($osm_profiler) $osm_profiler->start('classes', 'cache');
                try {
                    /** @noinspection PhpIncludeInspection */
                    $osm_classes->items = json_decode(file_get_contents($classFilename), true);
                    $osm_classes->modified = false;
                }
                finally {
                    if ($osm_profiler) $elapsed += $osm_profiler->stop('classes');
                }
            }
        }

        if (file_exists($filename)) {
            try {
                /* @var App $app */
                if ($osm_profiler) $osm_profiler->start('app', 'cache');
                try {
                    $osm_app = unserialize(file_get_contents($filename));
                }
                finally {
                    if ($osm_profiler) $elapsed += $osm_profiler->stop('app');
                }
                $osm_app->set($data);
                $osm_app->error_handler = $app->error_handler;

                static::includeGeneratedClasses();
                $osm_app->properties = Properties::new();
                if ($osm_profiler) {
                    $osm_profiler->record(App::class . '::createApp', 'lifecycle',
                        $osm_profiler->elapsed($osm_app->started_at) - $elapsed);
                }
                return $osm_app;
            }
            catch (\Throwable $e) {
                // in case loading fast loading from cache failed, continue as if cache doesn't exist. In most
                // cases cache is being deleted at this very moment
            }
        }


        if (file_exists($componentIgnoreFilename)) {
            $app->component_ignore = file($componentIgnoreFilename, FILE_IGNORE_NEW_LINES);
        }
        $app->packages = (new PackageLoader())->load();
        $app->modules = (new ModuleLoader())->load();

        static::includeGeneratedClasses();
        $app->saveClasses();

        try {
            file_put_contents(osm_make_dir_for($filename), serialize($app));
            @chmod($filename, $app->writable_file_permissions);
        }
        catch (\Exception $e) {
            @unlink($filename);
            throw $e;
        }

        file_put_contents(osm_make_dir_for($versionFilename), Str::random(8));
        @chmod($versionFilename, $app->writable_file_permissions);

        if ($osm_profiler) {
            $osm_profiler->record(App::class . '::createApp', 'lifecycle',
                $osm_profiler->elapsed($app->started_at));
        }
        $osm_app->properties = Properties::new();

        return $app;
    }

    protected static function includeGeneratedClasses() {
        $compiler = new Compiler();
        if (!file_exists($compiler->filename)) {
            $compiler->compile();
        }
        $compiler->includeGeneratedClasses();
    }


    public function default($property) {
        switch ($property) {
            case 'config_path': return 'config';
            case 'environment_path': return '';
            case 'data_path': return 'data';
            case 'temp_path': return 'temp/' . env('APP_ENV');
            case 'public_path': return env('APP_ENV') == 'testing' ? 'public/testing' : 'public';
            case 'umask': return 0;
            case 'readonly_directory_permissions': return 0755;
            case 'readonly_file_permissions': return 0644;
            case 'writable_directory_permissions': return 0777;
            case 'writable_file_permissions': return 0666;
            case 'component_ignore': return env('COMPONENT_IGNORE', []);
        }
        return parent::default($property);
    }

    public function boot() {
        global $osm_profiler; /* @var Profiler $osm_profiler */

        if ($osm_profiler) $osm_profiler->start(__METHOD__, 'lifecycle');
        try {
            foreach ($this->modules as $module) {
                $module->boot();
            }
            return $this;
        }
        finally {
            if ($osm_profiler) $osm_profiler->stop(__METHOD__);
        }
    }

    public function terminate() {
        global $osm_profiler; /* @var Profiler $osm_profiler */

        if ($osm_profiler) $osm_profiler->start(__METHOD__, 'lifecycle');
        try {
            foreach ($this->modules as $module) {
                $module->terminate();
            }

            if ($this->modified) {
                $filename = $this->path("{$this->temp_path}/cache/app.ser");
                file_put_contents(osm_make_dir_for($filename), serialize($this));
                @chmod($filename, $this->writable_file_permissions);

                $this->modified = false;
            }

            $this->saveClasses();
        }
        finally {
            if ($osm_profiler) $osm_profiler->stop(__METHOD__);
        }

        if ($osm_profiler) $osm_profiler->terminate();

        return $this;
    }

    protected function saveClasses() {
        global $osm_classes; /* @var Classes $osm_classes */

        if ($osm_classes->modified) {
            $classFilename = $this->path("{$this->temp_path}/cache/classes.json");
            file_put_contents(osm_make_dir_for($classFilename), json_encode($osm_classes->items));
            @chmod($classFilename, $this->writable_file_permissions);
            $osm_classes->modified = false;
        }
    }

    public static function runApp($mainModule, $data) {
        global $osm_app; /* @var App $osm_app */
        global $osm_profiler; /* @var Profiler $osm_profiler */

        $currentApp = $osm_app;
        $currentProfiler = $osm_profiler;

        try {
            $app = static::createApp($data);
            $app->boot();

            $app->main_module = $mainModule;
            $app->$mainModule->run();

            if ($e = $app->pending_exception) {
                $app->pending_exception = null;
                throw $e;
            }

            $app->terminate();
            return $app;
        }
        finally {
            $osm_app = $currentApp;
            $osm_profiler = $currentProfiler;
        }
    }

    public function path($path = '') {
        return $this->base_path . ($path ? "/$path" : '');
    }

    public function config($name) {
        return (new ConfigLoader(['name' => $name]))->load();
    }

    public function create($class, $data = null, $name = null, $parent = null) {
        if (is_object($data)) {
            $data = osm_non_nulls($data);
        }

        if (!is_array($data)) {
            $data = ['class' => $data ?: $class];
        }

        if ($name !== null) {
            $data['name'] = $name;
        }

        if ($parent !== null) {
            $data['parent'] = $parent;
        }

        $actualClass = $data['class'] ?? $class;
        unset($data['class']);

        $instance = $this->createRaw($actualClass, $data);

        if (!is_a($instance, $class)) {
            throw new FactoryError("Class " . get_class($instance). "should implement/subclass '$class'");
        }

        return $instance;
    }

    public function createRaw($class, ...$args) {
        $class = $this->class_names[$class] ?? $class;

        return new $class(...$args);
    }

    public function singleton($class) {
        if (!isset($this->singletons[$class])) {
            $this->singletons[$class] = $this->create($class);
        }

        return $this->singletons[$class];
    }

    public function offsetGet($class) {
        return $this->singleton($class);
    }

    public function getEnv($name, $default = null) {
        return env($name, $default);
    }

    public function ignore($filename) {
        if (empty($this->component_ignore)) {
            return false;
        }

        if (strpos($filename, $this->base_path) !== 0) {
            return true;
        }
        $filename = substr($filename, strlen($this->base_path) + 1);

        $result = false;
        foreach ($this->component_ignore as $pattern) {
            if (strpos($pattern, '#') === 0) {
                continue;
            }

            $negative = false;
            if (strpos($pattern, '!') === 0) {
                $pattern = substr($pattern, 1);
                $negative = true;
            }

            if ($result xor $negative) {
                continue;
            }

            $pattern = Glob::toRegex($pattern);
            if (preg_match($pattern, $filename)) {
                $result = !$result;
            }
        }

        return $result;
    }
}