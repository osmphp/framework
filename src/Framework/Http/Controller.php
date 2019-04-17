<?php

namespace Manadev\Framework\Http;

use Manadev\Core\App;
use Manadev\Core\Object_;
use Manadev\Framework\Areas\Area;

/**
 * @property Controllers $parent @required
 * @property string $method @required @part
 * @property string $returns @required @part
 * @property array $parameters @part Expected route parameter definitions
 * @property Parameters|Parameter[] $parameters_ @required @part
 * @property array $query @required Parsed area-wide and route-specific parameters in current request
 * @property bool $public @part
 * @property bool $abstract @part
 *
 * @property Request $request @required
 * @property Area $area @required
 * @property string $route @required
 */
class Controller extends Object_
{
    protected function default($property) {
        global $m_app; /* @var App $m_app */

        switch ($property) {
            case 'area': return $this->parent->area_;
            case 'returns': return Returns::HTML;
            case 'request': return $m_app->request;
            case 'parameters_': return Parameters::new(['config_' => $this->parameters ?? []]);
            case 'query':
                $parsedQuery = $this->area->query;
                foreach ($this->parameters_ as $parameter) {
                    if (($value = $parameter->parse($this->request->query)) !== null) {
                        $parsedQuery[$parameter->name] = $value;
                    }
                }
                return $parsedQuery;
            case 'route': return $this->request->route;
        }

        return parent::default($property);
    }
}