<?php

declare(strict_types=1);

namespace Osm\Framework\Http;

use Osm\Core\App;
use Osm\Core\Attributes\Name;
use Osm\Core\BaseModule;
use Osm\Framework\Areas;
use Osm\Framework\Areas\Attributes\Area;
use Osm\Framework\Http\Advices\Advice;
use Osm\Framework\Logs\Logs;
use Osm\Framework\Settings\Hints\Settings;
use Symfony\Component\HttpFoundation\Response;
use Osm\Framework\Cache\Attributes\Cached;
use function Osm\get_descendant_classes;

/**
 * @property array $advice_class_names #[Cached('http_advice_class_names')]
 * @property array $routes #[Cached('http_routes')]
 * @property array $dynamic_routes #[Cached('http_dynamic_routes')]
 */
class Module extends BaseModule
{
    public static array $requires = [
        Areas\Module::class,
        \Osm\Framework\Logs\Module::class,
        \Osm\Framework\Settings\Module::class,
    ];

    public static array $traits = [
        App::class => Traits\AppTrait::class,
        Settings::class => Traits\SettingsTrait::class,
        Areas\Area::class => Traits\Areas\AreaTrait::class,
        Areas\Api::class => Traits\Areas\ApiTrait::class,
        Logs::class => Traits\LogsTrait::class,
    ];

    public function around(callable $next, string $areaClassName = null,
        int $index = 0): Response
    {
        $areaClassName = $areaClassName ?? '';
        $adviceClassNames = $this->advice_class_names[$areaClassName] ?? [];

        if ($index >= count($adviceClassNames)) {
            return $next();
        }

        $new = "{$adviceClassNames[$index]}::new";

        return $this->around(fn () => $new()->around($next), $areaClassName,
            $index + 1);
    }

    /** @noinspection PhpUnused */
    protected function get_advice_class_names(): array {
        $classNames = [];

        foreach (get_descendant_classes(Advice::class) as $class) {
            if (!isset($class->attributes[Area::class])) {
                continue;
            }

            foreach ($class->attributes[Area::class] as $area) {
                /* @var Area $area */
                $areaClassName = $area->class_name ?? '';

                if (!isset($classNames[$areaClassName])) {
                    $classNames[$areaClassName] = [];
                }

                $classNames[$areaClassName][] = [
                    'class_name' => $class->name,
                    'sort_order' => $area->sort_order,
                ];
            }
        }

        foreach (array_keys($classNames) as $areaClassName) {
            usort($classNames[$areaClassName],
                fn($a, $b) => $a['sort_order'] <=> $b['sort_order']);

            $classNames[$areaClassName] = array_map(fn($a) => $a['class_name'],
                $classNames[$areaClassName]);
        }

        return $classNames;
    }

    /** @noinspection PhpUnused */
    protected function get_routes(): array {
        $this->loadRoutes();

        return $this->routes;
    }

    /** @noinspection PhpUnused */
    protected function get_dynamic_routes(): array {
        $this->loadRoutes();

        return $this->dynamic_routes;
    }

    protected function loadRoutes() {
        $this->routes = [];
        $this->dynamic_routes = [];

        foreach (get_descendant_classes(Route::class) as $class) {
            if (!isset($class->attributes[Area::class])) {
                continue;
            }

            foreach ($class->attributes[Area::class] as $area) {
                /* @var Area $area */
                if (!$area->class_name) {
                    continue;
                }


                /* @var Name $name */
                if ($name = $class->attributes[Name::class] ?? null) {
                    if (!isset($this->routes[$area->class_name])) {
                        $this->routes[$area->class_name] = [];
                    }

                    $this->routes[$area->class_name][$name->name] = $class->name;
                }
                else {
                    if (!isset($this->dynamic_routes[$area->class_name])) {
                        $this->dynamic_routes[$area->class_name] = [];
                    }

                    $this->dynamic_routes[$area->class_name][] = [
                        'class_name' => $class->name,
                        'sort_order' => $area->sort_order,
                    ];
                }
            }
       }

        foreach (array_keys($this->dynamic_routes) as $areaClassName) {
            usort($this->dynamic_routes[$areaClassName],
                fn($a, $b) => $a['sort_order'] <=> $b['sort_order']);

            $this->dynamic_routes[$areaClassName] = array_map(
                fn($a) => $a['class_name'],
                $this->dynamic_routes[$areaClassName]);
        }
    }
}