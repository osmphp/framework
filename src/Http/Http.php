<?php

declare(strict_types=1);

namespace Osm\Framework\Http;

use Osm\Core\App;
use Osm\Core\BaseModule;
use Osm\Core\Object_;
use Osm\Framework\Areas\Area;
use Osm\Framework\Http\Exceptions\NotFound;
use Osm\Framework\Http\Hints\BaseUrl;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @property Request $request
 * @property string $base_url
 * @property string $path
 * @property string $area_class_name
 * @property string $route_class_name
 * @property Module $module
 * @property Area $area
 * @property Route $route
 * @property BaseUrl[] $base_urls
 */
class Http extends Object_
{
    public function run(): Response {
        return $this->module->around(function() {
            $this->detectArea();

            return $this->module->around(function() {
                $this->detectRoute();

                return $this->route->run();
            }, $this->area_class_name);
        });
    }

    /** @noinspection PhpUnused */
    protected function get_request(): Request {
        return Request::createFromGlobals();
    }

    /** @noinspection PhpUnused */
    protected function get_module(): BaseModule {
        global $osm_app; /* @var App $osm_app */

        return $osm_app->modules[Module::class];
    }

    /** @noinspection PhpUnused */
    protected function get_area(): Area {
        $new = "{$this->area_class_name}::new";

        return $new();
    }

    /** @noinspection PhpUnused */
    protected function get_route(): Route {
        $new = "{$this->route_class_name}::new";

        return $new();
    }

    protected function detectArea() {
        $url = "{$this->base_url}{$this->request->getPathInfo()}";
        $placeholderUrl = "{{base_url}}{$this->request->getPathInfo()}";

        foreach ($this->base_urls as $baseUrl) {
            if (str_starts_with($url, $baseUrl->base_url))
            {
                $this->path = substr($url, strlen($baseUrl->base_url));
            }
            elseif (str_starts_with($placeholderUrl, $baseUrl->base_url))
            {
                $this->path = substr($placeholderUrl, strlen($baseUrl->base_url));
            }
            else {
                continue;
            }

            foreach ($baseUrl as $property => $value) {
                if ($property == 'base_url') {
                    continue;
                }

                $this->$property = $value;
            }

            return;
        }

        throw new NotFound();
    }

    protected function detectRoute() {
        $routeName = "{$this->request->getMethod()} {$this->path}";
        if ($this->route_class_name =
            $this->module->routes[$this->area_class_name][$routeName] ?? null)
        {
            return;
        }

        foreach ($this->module->dynamic_routes[$this->area_class_name] ?? []
            as $routeClassName)
        {
            $new = "{$routeClassName}::new";
            $route = $new();
            if (!$route->match()) {
                continue;
            }

            $this->route_class_name = $routeClassName;
            $this->route = $route;
            return;
        }

        throw new NotFound();
    }

    /** @noinspection PhpUnused */
    protected function get_base_urls(): array {
        global $osm_app; /* @var App $osm_app */

        return $osm_app->base_urls;
    }

    /** @noinspection PhpUnused */
    protected function get_base_url(): string {
        return $this->request->getSchemeAndHttpHost() .
            $this->request->getBaseUrl();
    }
}