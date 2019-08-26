<?php

namespace Osm\Core\Packages;

use Osm\Core\App;
use Osm\Core\Exceptions\InvalidPackage;
use Osm\Core\Object_;

class PackageLoader extends Object_
{
    public function load() {
        global $m_app; /* @var App $m_app */

        $result = [];

        foreach (glob($m_app->path('vendor/*/*/composer.json')) as $filename) {
            $this->loadPackage($result, $filename);
        }

        $this->loadPackage($result, $m_app->path('composer.json'), ['project' => true]);

        return $result;
    }

    protected function loadPackage(&$result, $filename, $data = []) {
        global $m_app; /* @var App $m_app */

        $json = json_decode(file_get_contents($filename), true);

        if (!($config = $json['extra']['osm'] ?? null)) {
            return;
        }

        if (empty($json['name'])) {
            throw new InvalidPackage("Package '($filename)' should have 'name' filled in.");
        }

        /* @var Package $package */
        $result[$json['name']] = $package = Package::new(array_merge($data, [
            'path' => str_replace('\\', '/',
                substr(dirname($filename), strlen($m_app->base_path) + 1)),
            'class' => $config['class'] ?? null
        ]), $json['name'], $m_app);

        $package->namespaces = $this->loadNamespaces($json);
        $package->component_pools = $this->loadComponentPools($package, $config['component_pools'] ?? []);
    }

    /**
     * @param Package $package
     * @param object[] $pools
     * @return ComponentPool[]
     */
    protected function loadComponentPools(Package $package, $pools) {
        $result = [];

        foreach ($pools as $name => $pool) {
            /* @var ComponentPool $pool */
            $result[$name] = $pool = ComponentPool::new(
                array_merge((array)$pool, [
                    'namespace' => isset($package->namespaces[$name])
                        ? str_replace('\\', '_', $package->namespaces[$name])
                        : null,
                ]), $name, $package);
        }

        return $result;
    }

    protected function loadNamespaces($json) {
        $result = [];
        if (!($namespaces = $json['autoload']['psr-4'] ?? null)) {
            return $result;
        }

        foreach ($namespaces as $namespace => $path) {
            $result[rtrim($path, '/')] = rtrim($namespace, '\\');
        }

        return $result;
    }
}