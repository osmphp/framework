<?php

namespace Osm\Ui\Breadcrumbs\Views;

use Osm\Core\App;
use Osm\Framework\Views\View;
use Osm\Ui\Breadcrumbs\Item;

/**
 * @property array $items @required @part
 * @property Item[] $items_ @required @part
 * @property string $page_url @required
 */
class Breadcrumbs extends View
{
    public $template = 'Osm_Ui_Breadcrumbs.breadcrumbs';

    protected function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'items_': return $this->getItems();
            case 'page_url': return $osm_app->request->symfony_request->getUri(); // TODO
        }

        return parent::default($property);
    }

    protected function getItems() {
        $result = [];

        foreach ($this->items as $name => $data) {
            $result[$name] = Item::new($data, $name, $this);
        }

        unset($this->items);

        return $result;
    }
}