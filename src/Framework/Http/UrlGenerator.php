<?php

namespace Manadev\Framework\Http;

use Manadev\Core\App;
use Manadev\Core\Object_;
use Manadev\Core\Profiler;
use Manadev\Framework\Areas\Area;

/**
 * @property Request $request @required
 * @property string $area @required
 * @property Area $area_ @required
 * @property string $base @required
 * @property string $asset_base @required
 * @property string $asset_version @required
 * @property string $env_path @required
 * @property Query|array $query @required Parsed query, depending on how far we progressed in processing HTTP request it
 *      can be full array of parsed query parameters, area-wide parameters if route is not detected yet or
 *      empty array
 */
class UrlGenerator extends Object_
{
    protected function default($property) {
        global $m_app; /* @var App $m_app */

        switch ($property) {
            case 'request': return $m_app->request;
            case 'area': return $m_app->area;
            case 'area_': return $m_app->areas[$this->area];
            case 'base': return $this->request->base;
            case 'asset_base': return $this->request->asset_base;
            case 'env_path': return env('APP_ENV') != 'production' ? env('APP_ENV') : '';
            case 'asset_version': return $this->getAssetVersion();
            case 'query': return $m_app->query;
        }
        return parent::default($property);
    }

    public function assetUrl($path) {
        global $m_app; /* @var App $m_app */
        return $this->asset_base . ($this->env_path ? "/{$this->env_path}" : '') .
            "/{$this->area}/{$m_app->theme}/{$path}?v={$this->asset_version}";
    }

    public function rawUrl($route, $rawQuery = []) {
        $queryString = '';

        foreach ($rawQuery as $key => $value) {
            $generated = $this->generateParameter($key, $value);

            if ($generated === '') {
                continue;
            }

            if ($queryString) {
                $queryString .= '&';
            }

            $queryString .= $generated;
        }


        return $this->base .
            substr($route, strpos($route, ' ') + 1) .
            ($queryString ? '?' . $queryString : '');
    }

    /**
     * @param string $route
     * @param array $parsedQuery
     * @param array $data
     * @return string
     */
    public function routeUrl($route, $parsedQuery = [], $data = []) {
        global $m_profiler; /* @var Profiler $m_profiler */

        if ($m_profiler) $m_profiler->start(__METHOD__, 'helpers');
        try {
            return $this->rawUrl($route, $this->generateQuery($route, $parsedQuery));
        }
        finally {
            if ($m_profiler) $m_profiler->stop(__METHOD__);
        }
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
        global $m_app; /* @var App $m_app */

        return file_get_contents($m_app->path('public' . ($this->env_path ? "/{$this->env_path}" : '') .
            "/{$this->area}/{$m_app->theme}/version.txt"));
    }


}