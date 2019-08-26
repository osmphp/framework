<?php

namespace Osm\Framework\Themes;

use Osm\Core\App;
use Osm\Core\Packages\Package;
use Osm\Framework\Data\CollectionRegistry;
use Osm\Framework\Themes\Exceptions\InvalidThemeDefinition;

/**
 * @property Package[] $packages
 */
class Themes extends CollectionRegistry
{
    public $class_ = Theme::class;
    public $not_found_message = "Theme ':name' not found";

    public function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'packages': return $osm_app->packages;
        }
        return parent::default($property);
    }

    protected function get() {
        $result = [];

        foreach ($this->packages as $package) {
            $this->getPackageThemes($result, $package);
        }

        $this->modified();

        return $result;
    }

    /**
     * @param Theme[] $result
     * @param Package $package
     * @throws InvalidThemeDefinition
     */
    protected function getPackageThemes(&$result, Package $package) {
        global $osm_app; /* @var App $osm_app */

        foreach ($package->component_pools as $pool) {
            if (!$pool->theme_path) {
                continue;
            }

            $path = $osm_app->path($package->path . ($pool->name ? "/$pool->name" : ''));
            foreach (glob("$path/{$pool->theme_path}") as $filename) {
                // make sure Osm\Framework\Themes\Theme class file Theme.php is not treated as theme definition
                // file theme.php. On Windows, file names are case-insensitive, so this may happen. Without this check,
                // you would see weird error "Cannot declare class Osm\Framework\Themes\Theme, because
                // the name is already in use"
                $dir = __DIR__;
                if (str_replace('\\', '/', dirname($filename)) == str_replace('\\', '/', $dir))
                {
                    continue;
                }

                /** @noinspection PhpIncludeInspection */
                $definition = include $filename;

                if (!isset($definition['name'])) {
                    throw new InvalidThemeDefinition(m_("Theme name should be specified in ':filename'", [
                        'filename' => $filename,
                    ]));
                }

                if (!isset($definition['area'])) {
                    throw new InvalidThemeDefinition(m_("Area name should be specified in ':filename'", [
                        'filename' => $filename,
                    ]));
                }

                if (isset($result[$definition['name']])) {
                    $theme = $result[$definition['name']];
                }
                else {
                    $theme = $result[$definition['name']] = Theme::new([
                        'definitions' => [],
                    ], $definition['name'], $this);
                }

                if (isset($definition['parent_theme'])) {
                    $theme->parent_theme = $definition['parent_theme'];
                }

                unset($definition['name']);
                unset($definition['parent_theme']);

                $definition['path'] = str_replace('\\', '/',
                    substr(dirname($filename), strlen($osm_app->base_path) + 1));

                $name = $pool->namespace . '_' . str_replace('/', '_', str_replace('\\', '/',
                    substr(dirname($filename), strlen($path) + 1)));

                $theme->definitions[$name] = Definition::new($definition, $name, $theme);

            }
        }
    }

}