<?php

namespace Manadev\Framework\Views\Views;

use Manadev\Core\App;
use Manadev\Framework\Http\Controller;
use Manadev\Framework\Http\Parameter;
use Manadev\Framework\Http\Request;
use Manadev\Framework\Http\UrlGenerator;
use Manadev\Framework\Views\JsConfig;
use Manadev\Framework\Views\View;

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
 * @property JsConfig $model @required
 * @property UrlGenerator $url_generator @required
 * @property Controller $controller @required
 */
class Page extends View
{
    public $template = 'Manadev_Framework_Views.page';

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

    protected function default($property) {
        global $m_app; /* @var App $m_app */

        switch ($property) {
            case 'model': return $this->js_config;
            case 'request': return $m_app->request;
            case 'url_generator': return $m_app->url_generator;
            case 'controller': return $m_app->controller;
        }
        return parent::default($property);
    }

    public function rendering() {
        $this->model->base_url = $this->request->base;
        $this->model->transient_query = (object)$this->url_generator->generateQuery(
            "{$this->request->method} {$this->controller->route}",
            $this->controller->query,
            function(Parameter $parameter) {
                return $parameter->transient;
            });
    }
}