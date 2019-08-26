<?php

namespace Osm\Framework\Http\Traits;

use Osm\Core\App;
use Osm\Framework\Areas\Area;
use Osm\Framework\Http\Advices;
use Osm\Framework\Http\Controllers;
use Osm\Framework\Http\Parameters;

trait AreaTrait
{
    protected function around_default(callable $proceed, $property) {
        global $osm_app; /* @var App $osm_app */

        /* @var Area $area */
        $area = $this;

        switch ($property) {
            case 'advices_': return $osm_app->cache->remember("http_advices.{$area->name}", function($data) use ($area) {
                $definitions = [];

                for (;$area; $area = $area->parent_area_) {
                    $definitions = m_merge($area->advices ?? [], $definitions);
                }

                return Advices::new(array_merge($data, ['config' => $definitions]));
            });
            case 'controllers': return $osm_app->cache->remember("routes.{$area->name}", function($data) {
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
                    if (($value = $parameter->parse($osm_app->request->query)) !== null) {
                        $parsedQuery[$parameter->name] = $value;
                    }
                }
                return $parsedQuery;
        }

        return $proceed($property);
    }
}