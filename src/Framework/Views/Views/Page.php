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
 * @property View $content @required @part View to be rendered in main content area
 *
 * Optional properties:
 *
 * @property View $header @part Header view
 * @property View $footer @part Footer view
 * @property string $html_modifier @part CSS classes to add to <html> element
 *
 * Dependencies:
 *
 * @property Request $request @required
 * @property Url $url @required
 * @property Controller $controller @required
 * @property Areas|Area[] $areas @required
 */
class Page extends View
{
    public $template = 'Osm_Framework_Views.page';

    /**
     * Page view doesn't have HTML id assigned, so HTML id of every child view is the same as its alias
     *
     * @var string
     */
    public $id_ = '';

    /**
     * View ID is preassigned, so you can easily reference page view using '#page' => [ ... ] instruction
     *
     * @var string
     */
    public $id = 'page';

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
}