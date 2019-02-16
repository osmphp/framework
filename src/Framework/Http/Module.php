<?php

namespace Manadev\Framework\Http;

use Manadev\Core\App;
use Manadev\Core\Profiler;
use Manadev\Core\Properties;
use Manadev\Framework\Areas\Area;
use Manadev\Core\Modules\BaseModule;
use Manadev\Framework\Http\Errors\Error;
use Manadev\Framework\Http\Errors\Errors;
use Manadev\Framework\Http\Exceptions\HttpError;
use Manadev\Framework\Http\Traits\AreaTrait;
use Symfony\Component\HttpFoundation\Response;

/**
 * @property Advices $advices @required
 * @property Responses $responses @required
 * @property Controller $controller @required
 * @property Errors|Error[] $errors @required
 */
class Module extends BaseModule
{
    public $short_name = 'http';

    public $hard_dependencies = [
        'Manadev_Framework_Areas',
    ];

    public $traits = [
        Properties::class => Traits\PropertiesTrait::class,
        Area::class => AreaTrait::class,
    ];

    public static function detectEnv() {
        global $_GET;

        return $_GET['_env'] ?? null;
    }

    protected function default($property) {
        global $m_app; /* @var App $m_app */

        switch ($property) {
            case 'advices': return $m_app->cache->remember('http_advices', function($data) {
                global $m_app; /* @var App $m_app */

                return Advices::new(array_merge($data, [
                    'config' => $m_app->config('http_advices'),
                ]));
            });
            case 'responses': return $m_app[Responses::class];
            case 'controller': return $m_app->controller;
            case 'errors': return $m_app->cache->remember('http_errors', function($data) {
                return Errors::new($data);
            });
        }
        return parent::default($property);
    }

    public function run() {
        global $m_app; /* @var App $m_app */
        global $m_profiler; /* @var Profiler $m_profiler */

        if ($m_profiler) $m_profiler->start(__METHOD__, 'lifecycle');
        try {
            $response = $this->advices->around(function() {
                global $m_app; /* @var App $m_app */

                return $m_app->area_->advices_->around(function() {
                    $method = $this->controller->method;
                    $returns = $this->controller->returns;

                    return $this->responses->response($returns, $this->controller->$method());
                });
            });
        }
        catch (\Throwable $e) {
            $response = $this->exception($e);
        }
        finally {
            if ($m_profiler) $m_profiler->stop(__METHOD__);
        }

        if ($e = $m_app->pending_exception) {
            $m_app->pending_exception = null;
            $response = $this->exception($e);
        }

        $m_app->response = $response;

        if (!$m_app->catch_output) {
            $response->send();
        }
    }

    public function exception(\Throwable $e) {
        if ($e instanceof HttpError) {
            return $this->error($this->errors[$e->error], $e);
        }

        return $this->error($this->errors['general'], $e);
    }

    protected function error(Error $error, \Throwable $e) {
        global $m_app; /* @var App $m_app */

        $error->e = $e;

        /* @var Response $response */
        $response = $m_app->createRaw(Response::class, $error->content, $error->status, [
            'content-type' => $error->content_type,
            'status-text' => $error->status_text,
        ]);

        return $response;
    }
}