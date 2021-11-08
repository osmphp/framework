<?php

declare(strict_types=1);

namespace Osm\Framework\Blade;

use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Compilers\BladeCompiler;
use Osm\Core\App;
use Osm\Core\Exceptions\NotImplemented;
use Osm\Framework\Themes\Theme;

class Compiler extends BladeCompiler
{
    public const AROUND_PATTERN = '/@around(?:\((?<search>.*)\))?\s+(?<replace>.*?)\s+@endaround/us';

    public function __construct(Filesystem $files, $cachePath,
        protected Theme $theme)
    {
        parent::__construct($files, $cachePath);
    }

    /**
     * Compile the component tags.
     *
     * @param  string  $value
     * @return string
     */
    protected function compileComponentTags($value)
    {
        if (! $this->compilesComponentTags) {
            return $value;
        }

        return $this->createComponentTagCompiler()->compile($value);
    }

    public function createComponentTagCompiler(): ComponentTagCompiler {
        return (new ComponentTagCompiler($this->classComponentAliases,
            $this->classComponentNamespaces, $this));
    }

    protected function getRootPath(): string {
        global $osm_app; /* @var App $osm_app */

        return "{$osm_app->paths->temp}/{$this->theme->name}/views/";
    }
    public function compileString($value)
    {
        global $osm_app; /* @var App $osm_app */

        if (str_starts_with($this->path, $this->getRootPath())) {
            $path = mb_substr($this->path, mb_strlen($this->getRootPath()));

            foreach ($osm_app->modules as $module) {
                $value = $this->compileTrait($module, $path, $value);
            }
        }

        return parent::compileString($value);
    }

    protected function compileTrait(mixed  $module, string $path,
        string $value): string
    {
        $rootPath = "{$this->getRootPath()}{$module->name}/traits/";

        if (!is_file("{$rootPath}{$path}")) {
            return $value;
        }

        $advices = $this->files->get("{$rootPath}{$path}");

        preg_replace_callback(static::AROUND_PATTERN,
            function($match) use (&$value) {
                $this->compileAround($value, $match['search'] ?? null,
                    $match['replace']);
                return $match[0];
            },
            $advices);

        return $value;
    }

    protected function compileAround(string &$value, ?string $search,
        string $replace): void
    {
        if (!$search) {
            $value = str_replace('@proceed', $value, $replace);
        }
        else {
            if (!str_starts_with($search, '/') || str_ends_with($search, '/')) {
                $search = '/' . preg_quote($search, '/'). '/';
            }
            $search .= 'us';

            $value = preg_replace_callback($search,
                fn($match) => str_replace('@proceed', $match[0], $replace),
                $value);
        }
    }
}