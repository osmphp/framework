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
 * @property string $title @required @part
 * @property View $header @part
 * @property View $content @required @part
 * @property View $footer @part
 * @property string $html_modifier @part
 * @property Request $request @required
 * @property JsConfig $model @required
 * @property UrlGenerator $url_generator @required
 * @property Controller $controller @required
 */
class Page extends View
{
    public $id_ = '';
    public $id = 'page';
    public $template = 'Manadev_Framework_Views.page';
    /**
     * @required @path
     * @var View[]
     */
    public $head_end = [];
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