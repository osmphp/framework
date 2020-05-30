<?php

namespace Osm\Framework\Http;

use Osm\Core\App;
use Osm\Core\Object_;
use Osm\Core\Profiler;
use Osm\Framework\Areas\Area;
use Osm\Framework\Areas\Areas;
use Osm\Framework\Settings\Settings;

/**
 * Constructor arguments:
 *
 * @property string $area @required Name of area in which routes for this
 *      URL generator are defined
 * @property string $base_url Absolute URL to prefix all generated URLs.
 *      Leave empty in constructor to take default value from `base_url` setting
 *      which, in turn, takes value of `APP_URL` environment variable.
 *      In case no value is provided in constructor and `base_url` setting
 *      is not assigned, it is inferred from incoming request, that is to
 *      `$request_base_url` property. If you plan to generate URLs in console
 *      do provide this property in constructor or assign `APP_URL` environment
 *      variable.
 * @property string $route_base_url Used to calculate `route_base_url_` property
 *      which contains absolute URL to prefix all generated route URLs.
 *      Leave empty, specify URL relative to `base_url` property or use
 *      absolute URL.
 * @property string $asset_base_url Absolute URL to prefix all asset URLs.
 *      If not provided, uses default value from `asset_base_url` setting
 *      which, in turn, takes value of `ASSET_URL` environment variable. Use
 *      this property to serve assets from CDN.
 *
 * Dependencies:
 *
 * @property Request $request @required Current HTTP request. This property
 *       is not available in console.
 * @property Area $area_ @required Area containing route definitions used by
 *      this URL generator
 * @property Areas|Area[] $areas @required
 * @property Settings $settings @required
 * @property string $request_base_url @required Base URL of incoming HTTP
 *      request. This property is not available in console.
 * @property string $asset_version @required
 * @property string $env_path @required
 * @property Query|array $query @required Parsed query, depending on how far we progressed in processing HTTP request it
 *      can be full array of parsed query parameters, area-wide parameters if route is not detected yet or
 *      empty array
 *
 * Calculated properties:
 *
 * @property string $route_base_url_ @required Absolute URL to prefix all route
 *      URLs. Calculated from `route_base_url` and `base_url` properties.
 */
class Url extends Object_
{
    protected function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'request': return $osm_app->request;
            case 'area': return $osm_app->area;
            case 'area_': return $osm_app->areas[$this->area];
            case 'areas': return $osm_app->areas;
            case 'settings': return $osm_app->settings;
            case 'request_base_url': return $this->request->base;
            case 'base_url': return (string)$this->settings->base_url
                ?: $this->request_base_url;
            case 'route_base_url_': return osm_is_absolute_url($this->route_base_url)
                ? $this->route_base_url
                : $this->base_url . $this->route_base_url;
            case 'asset_base_url': return $this->base_url;
            case 'env_path': return env('APP_ENV') != 'production' ? env('APP_ENV') : '';
            case 'asset_version': return $this->getAssetVersion();
            case 'query': return $osm_app->query;
        }
        return parent::default($property);
    }

    public function toAsset($path) {
        global $osm_app; /* @var App $osm_app */
        return $this->asset_base_url . ($this->env_path ? "/{$this->env_path}" : '') .
            "/{$this->area}/{$osm_app->theme}/{$path}?v={$this->asset_version}";
    }

    public function to($route, $rawQuery = []) {
        return $this->route_base_url_ .
            substr($route, strpos($route, ' ') + 1) .
            $this->generateQueryString($rawQuery);
    }

    public function generateQueryString($rawQuery = []) {
        $result = '';

        foreach ($rawQuery as $key => $value) {
            $generated = $this->generateParameter($key, $value);

            if ($generated === '') {
                continue;
            }

            if ($result) {
                $result .= '&';
            }

            $result .= $generated;
        }


        return $result ? '?' . $result : '';
    }

    /**
     * @param string $route
     * @param array $parsedQuery
     * @return string
     */
    public function toRoute($route, $parsedQuery = []) {
        global $osm_profiler; /* @var Profiler $osm_profiler */

        if ($osm_profiler) $osm_profiler->start(__METHOD__, 'helpers');
        try {
            return $this->to($route, $this->generateQuery($route, $parsedQuery));
        }
        finally {
            if ($osm_profiler) $osm_profiler->stop(__METHOD__);
        }
    }

    public function toAreaRoute($area, $route, $parsedQuery = []) {
        if ($area == $this->area) {
            return $this->toRoute($route, $parsedQuery);
        }

        return $this->areas[$area]->url->toRoute($route, $parsedQuery);
    }

    public function generateQuery($route, $parsedQuery = [], callable $filterCallback = null) {
        $rawQuery = [];

        foreach ($this->area_->controllers[$route]->parameters_ as $parameter) {
            $this->generateParsedParameter($parameter, $rawQuery, $parsedQuery, $filterCallback);
        }
        foreach ($this->area_->parameters_ as $parameter) {
            $this->generateParsedParameter($parameter, $rawQuery, $parsedQuery, $filterCallback);
        }

        return $rawQuery;
    }

    protected function generateParsedParameter(Parameter $parameter, &$rawQuery, $parsedQuery = [],
        callable $filterCallback = null)
    {
        if ($filterCallback && !$filterCallback($parameter)) {
            return;
        }

        if (($value = $parsedQuery[$parameter->name] ??
            ($parameter->transient ? $this->query[$parameter->name] ?? null : null)) === null)
        {
            return;
        }

        $parameter->generate($rawQuery, $value);
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return string
     */
    protected function generateParameter($key, $value) {
        if (is_array($value)) {
            return $this->generateArray($key, $value);
        }

        return $this->generateValue($key, $value);
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return string
     */
    protected function generateValue($key, $value) {
        if ($value === null) {
            return '';
        }

        if ($value === true) {
            return $this->encode($key);
        }

        return $this->encode($key) . '=' . $this->encode($value);
    }

    /**
     * @param string $key
     * @param array $value
     * @return string
     */
    protected function generateArray($key, $value) {
        $result = '';

        foreach ($value as $item) {
            if ($item === null) {
                continue;
            }

            if ($result) {
                $result .= '&';
            }

            $result .= $this->encode($key) . '=' . $this->encode($item);
        }

        return $result;

    }

    public function encode($url) {
        return str_replace('%20', '+', rawurlencode($url));
    }

    /**
     * @return bool|string
     */
    protected function getAssetVersion() {
        global $osm_app; /* @var App $osm_app */

        return file_get_contents($osm_app->path('public' . ($this->env_path ? "/{$this->env_path}" : '') .
            "/{$this->area}/{$osm_app->theme}/version.txt"));
    }
}