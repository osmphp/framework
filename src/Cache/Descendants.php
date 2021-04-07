<?php

declare(strict_types=1);

namespace Osm\Framework\Cache;

use Osm\Core\Attributes\Name;
use Osm\Core\Class_;
use Osm\Core\Object_;
use function Osm\get_descendant_classes;

/**
 * @property Cache $cache
 */
class Descendants extends Object_
{
    protected array $classes = [];
    protected array $byName = [];
    protected array $all = [];

    /**
     * @param string $className
     * @return string[]
     */
    public function byName(string $className): array {
        if (!isset($this->byName[$className])) {
            $classNames = [];

            foreach ($this->classes($className) as $class) {
                /* @var Name $name */
                if ($name = $class->attributes[Name::class] ?? null) {
                    $classNames[$name->name] = $class->name;
                }
            }

            $this->byName[$className] = $classNames;
        }

        return $this->byName[$className];
    }

    /**
     * @param string $className
     * @return string[]
     */
    public function all(string $className): array {
        if (!isset($this->all[$className])) {
          $this->all[$className] = array_map(
                fn(Class_ $class) => $class->name,
                $this->classes($className));
        }

        return $this->all[$className];
    }

    /**
     * @param string $className
     * @return Class_[]
     */
    public function classes(string $className): array {
        if (!isset($this->classes[$className])) {
            $key = 'descendants__' . strtr($className, '\\', '_');

            $this->classes[$className] = $this->cache->get($key,
                fn() => get_descendant_classes($className));
        }

        return $this->classes[$className];
    }
}