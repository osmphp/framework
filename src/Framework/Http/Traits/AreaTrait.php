<?php

namespace Manadev\Framework\Http\Traits;

use Manadev\Core\App;
use Manadev\Framework\Areas\Area;
use Manadev\Framework\Http\Advices;
use Manadev\Framework\Http\Controllers;
use Manadev\Framework\Http\Parameters;

trait AreaTrait
{
    protected function around_default(callable $proceed, $property) {
        global $m_app; /* @var App $m_app */

        /* @var Area $area */
        $area = $this;

        switch ($property) {
            case 'advices_': return $m_app->cache->remember("http_advices.{$area->name}", function($data) use ($area) {
                $definitions = [];

                for (;$area; $area = $area->parent_area_) {
                    $definitions = m_merge($area->advices ?? [], $definitions);
                }

                return Advices::new(array_merge($data, ['config' => $definitions]));
            });
            case 'controllers': return $m_app->cache->remember("routes.{$area->name}", function($data) {
                return Controllers::new(array_merge($data, ['area' => $this->name]));
            });
            case 'parameters_':
                $definitions = [];

                for (;$area; $area = $area->parent_area_) {
                    $definitions = m_merge($area->parameters ?? [], $definitions);
                }

                return Parameters::new(['config_' => $definitions]);
            case 'query':
                $parsedQuery = [];
                foreach ($area->parameters_ as $parameter) {
                    if (($value = $parameter->parse($m_app->request->query)) !== null) {
                        $parsedQuery[$parameter->name] = $value;
                    }
                }
                return $parsedQuery;
        }

        return $proceed($property);
    }
}