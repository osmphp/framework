<?php

namespace Osm\Framework\Views\Views;

use Osm\Core\App;
use Osm\Framework\Areas\Area;
use Osm\Framework\Areas\Areas;
use Osm\Framework\Http\Controller;
use Osm\Framework\Http\Parameter;
use Osm\Framework\Http\Request;
use Osm\Framework\Http\Url;
use Osm\Framework\Views\View;

/**
 * Renders HTML page.
 *
 * Assign the following properties in every page type layer:
 *
 * @property string $title @required @part Page meta title
 *
 * Optional properties:
 *
 * @property string $html_modifier @part CSS classes to add to <html> element
 * @property string $canonical_url @part
 *
 * Dependencies:
 *
 * @property Request $request @required
 * @property Url $url @required
 * @property Controller $controller @required
 * @property Areas|Area[] $areas @required
 */
class Page extends Container
{
    public $template = 'Osm_Framework_Views.page';

    /**
     * Page view doesn't have HTML id assigned, so HTML id of every child view is the same as its alias
     *
     * @var string
     */
    public $id_ = '';

    /**
     * Add views to be rendered before closing </head>
     *
     * @required @part
     * @var View[]
     */
    public $head_end = [];
    /**
     * Add views to be rendered before closing </head>
     *
     * @required @part
     * @var View[]
     */
    public $body_end = [];

    /**
     * @var string[]
     */
    public $translations = [];

    protected function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'request': return $osm_app->request;
            case 'url': return $osm_app->url;
            case 'controller': return $osm_app->controller;
            case 'areas': return $osm_app->areas;

            case 'empty': return false;
            case 'debug_selector': return 'body';
        }
        return parent::default($property);
    }

    public function rendering() {
        $this->model = osm_merge([
            'base_url' => $this->url->route_base_url_,
            'base_urls' => (object)$this->renderBaseUrls(),
            'transient_query' => (object)$this->renderTransientQuery(),
            'translations' => (object)$this->renderTranslations(),
        ], $this->model ?: []);
    }

    protected function renderBaseUrls() {
        $result = [];

        foreach ($this->areas as $area) {
            if ($area == $this->url->area_) {
                continue;
            }

            if (!isset($area->url)) {
                continue;
            }

            $result[$area->name] = $area->url->route_base_url_;
        }

        return $result;
    }

    protected function renderTransientQuery() {
        return $this->url->generateQuery(
            "{$this->request->method} {$this->controller->route}",
            $this->controller->query,
            function(Parameter $parameter) { return $parameter->transient; }
        );
    }

    protected function renderTranslations() {
        return array_map(function($s) {
            return (string)osm_t($s);
        }, $this->translations);
    }

    protected function addDebugViewModel(&$result, $template) {
        if (($pos = mb_strpos($result, '</body>')) !== false) {
            $result = mb_substr($result, 0, $pos) .
                $this->getDebugScript($template) .
                mb_substr($result, $pos);
        }
        else {
            $result .= $this->getDebugScript($template);
        }
    }
}