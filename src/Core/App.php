<?php

namespace Manadev\Core;

use Illuminate\Support\Str;
use Manadev\Data\Formulas\Functions\Function_;
use Manadev\Data\Formulas\Functions\Functions;
use Manadev\Data\OptionLists\OptionList;
use Manadev\Data\OptionLists\OptionLists;
use Manadev\Data\Sheets\Query;
use Manadev\Data\Sheets\Sheets;
use Manadev\Data\TableQueries\TableQuery;
use Manadev\Framework\Areas\Area;
use Manadev\Framework\Areas\Areas;
use Manadev\Framework\Cache\Cache;
use Manadev\Framework\Cache\Caches;
use Manadev\Core\Classes\Classes;
use Manadev\Core\Compilation\Compiler;
use Manadev\Core\Config\ConfigLoader;
use Manadev\Core\Environment\EnvironmentLoader;
use Manadev\Core\ErrorHandling\ErrorHandler;
use Manadev\Core\Exceptions\FactoryError;
use Manadev\Core\Modules\BaseModule;
use Manadev\Core\Modules\ModuleLoader;
use Manadev\Core\Packages\Package;
use Manadev\Core\Packages\PackageLoader;
use Manadev\Framework\Cron\Job;
use Manadev\Framework\Cron\Jobs;
use Manadev\Framework\Db\Databases;
use Manadev\Framework\Db\Db;
use Manadev\Framework\Encryption\Hashing\Hashing;
use Manadev\Framework\Http\Controller;
use Manadev\Framework\Http\Query as HttpQuery;
use Manadev\Framework\Http\Request;
use Manadev\Framework\Http\UrlGenerator;
use Manadev\Framework\Layers\Layout;
use Manadev\Framework\Logging\Logs;
use Manadev\Framework\Queues\Queue;
use Manadev\Framework\Queues\Queues;
use Manadev\Framework\Sessions\Session;
use Manadev\Framework\Sessions\Stores;
use Manadev\Framework\Sessions\Stores\Store;
use Manadev\Framework\Settings\Settings;
use Manadev\Framework\Themes\Theme;
use Manadev\Framework\Themes\Themes;
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
 * @see \Manadev\Framework\Cache\Module:
 *      @property Caches|Cache[] $caches @required @default
 *      @property Cache $cache Main cache @required @default
 * @see \Manadev\Framework\Settings\Module:
 *      @property Settings $settings @required @default
 * @see \Manadev\Framework\Db\Module:
 *      @property Databases|Db[] $databases @required @default
 *      @property Db|TableQuery[] $db @required @default
 * @see \Manadev\Framework\Http\Module:
 *      @property Request $request @required @default
 *      @property UrlGenerator $url_generator @required @default
 *      @property Controller $controller @required
 *      @property HttpQuery|array $query @required @default
 * @see \Manadev\Framework\Areas\Module:
 *      @property string $area @required
 *      @property Areas|Area[] $areas @required @default
 *      @property Area $area_ @required @default
 * @see \Manadev\Framework\Themes\Module:
 *      @property string $theme @required @default
 *      @property Themes $themes @required @default
 *      @property Theme $theme_ @required @default
 * @see \Manadev\Framework\Sessions\Module:
 *      @property Stores|Store[] $session_stores @required @default
 *      @property Session $session @required
 * @see \Manadev\Framework\Layers\Module:
 *      @property Layout $layout @required
 * @see \Manadev\Framework\Queues\Module:
 *      @property Queues|Queue[] $queues @required @default
 * @see \Manadev\Framework\Cron\Module:
 *      @property Jobs|Job[] $cron_jobs @required @default
 * @see \Manadev\Framework\Cron\Module:
 *      @property Hashing $hashing @required @default
 * @see \Manadev\Framework\Logging\Module:
 *      @property Logs $logs @required @default
 * @see \Manadev\Data\OptionLists\Module:
 *      @property OptionLists|OptionList[] $option_lists @required @default
 * @see \Manadev\Data\TableQueries\Module:
 *      @property Functions|Function_[] $table_functions @required @default
 * @see \Manadev\Data\Sheets\Module:
 *      @property Sheets|Query[] $sheets @required @default
 *
 * Module shortcuts:
 *
 * @property \Manadev\Framework\Laravel\Module $laravel @required
 * @property \Manadev\Framework\Localization\Module $localization @required
 * @property \Manadev\Framework\Testing\Module $testing @required
 * @property \Manadev\Framework\Console\Module $console @required
 * @property \Manadev\Framework\Http\Module $http @required
 */
class App extends Object_
{
    public $singletons = [];

    /**
     * @var Properties
     */
    public $properties = null;

    public static function createApp($data = []) {
        global $m_app; /* @var App $m_app */
        global $m_classes; /* @var Classes $m_classes */
        global $m_profiler; /* @var Profiler $m_profiler */

        $m_classes = new Classes();

        $data['started_at'] = microtime(true);

        $m_app = $app = new static($data);

        umask($app->umask);
        (new EnvironmentLoader())->load();
        $m_profiler = env('PROFILE', false) ? new Profiler() : null;
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
                if ($m_profiler) $m_profiler->start('classes', 'cache');
                try {
                    /** @noinspection PhpIncludeInspection */
                    $m_classes->items = json_decode(file_get_contents($classFilename), true);
                    $m_classes->modified = false;
                }
                finally {
                    if ($m_profiler) $elapsed += $m_profiler->stop('classes');
                }
            }
        }

        if (file_exists($filename)) {
            try {
                /* @var App $app */
                if ($m_profiler) $m_profiler->start('app', 'cache');
                try {
                    $m_app = unserialize(file_get_contents($filename));
                }
                finally {
                    if ($m_profiler) $elapsed += $m_profiler->stop('app');
                }
                $m_app->set($data);
                $m_app->error_handler = $app->error_handler;

                static::includeGeneratedClasses();
                $m_app->properties = Properties::new();
                if ($m_profiler) {
                    $m_profiler->record(App::class . '::createApp', 'lifecycle',
                        $m_profiler->elapsed($m_app->started_at) - $elapsed);
                }
                return $m_app;
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
            file_put_contents(m_make_dir_for($filename), serialize($app));
            @chmod($filename, $app->writable_file_permissions);
        }
        catch (\Exception $e) {
            @unlink($filename);
            throw $e;
        }

        file_put_contents(m_make_dir_for($versionFilename), Str::random(8));
        @chmod($versionFilename, $app->writable_file_permissions);

        if ($m_profiler) {
            $m_profiler->record(App::class . '::createApp', 'lifecycle',
                $m_profiler->elapsed($app->started_at));
        }
        $m_app->properties = Properties::new();

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
        global $m_profiler; /* @var Profiler $m_profiler */

        if ($m_profiler) $m_profiler->start(__METHOD__, 'lifecycle');
        try {
            foreach ($this->modules as $module) {
                $module->boot();
            }
            return $this;
        }
        finally {
            if ($m_profiler) $m_profiler->stop(__METHOD__);
        }
    }

    public function terminate() {
        global $m_profiler; /* @var Profiler $m_profiler */

        if ($m_profiler) $m_profiler->start(__METHOD__, 'lifecycle');
        try {
            foreach ($this->modules as $module) {
                $module->terminate();
            }

            if ($this->modified) {
                $filename = $this->path("{$this->temp_path}/cache/app.ser");
                file_put_contents(m_make_dir_for($filename), serialize($this));
                @chmod($filename, $this->writable_file_permissions);

                $this->modified = false;
            }

            $this->saveClasses();
        }
        finally {
            if ($m_profiler) $m_profiler->stop(__METHOD__);
        }

        if ($m_profiler) $m_profiler->terminate();

        return $this;
    }

    protected function saveClasses() {
        global $m_classes; /* @var Classes $m_classes */

        if ($m_classes->modified) {
            $classFilename = $this->path("{$this->temp_path}/cache/classes.json");
            file_put_contents(m_make_dir_for($classFilename), json_encode($m_classes->items));
            @chmod($classFilename, $this->writable_file_permissions);
            $m_classes->modified = false;
        }
    }

    public static function runApp($mainModule, $data) {
        global $m_app; /* @var App $m_app */
        global $m_profiler; /* @var Profiler $m_profiler */

        $currentApp = $m_app;
        $currentProfiler = $m_profiler;

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
            $m_app = $currentApp;
            $m_profiler = $currentProfiler;
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
            $data = m_non_nulls($data);
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