<?php

namespace Osm\Ui\Menus\Views;

use Osm\Core\App;
use Osm\Core\Exceptions\PropertyNotSet;
use Osm\Framework\Data\Sorter;
use Osm\Framework\Views\View;
use Osm\Ui\Menus\Items\Item;
use Osm\Ui\Menus\Items\Type;
use Osm\Ui\Menus\Items\Types;
use Osm\Ui\Menus\Module;

/**
 * @property bool $items_can_be_checked @required
 * @property array $items @required @part
 * @property Item[] $items_ @required @part
 *
 * @property Types|Type[] $item_types @required
 * @property Module $module @required
 * @property Sorter $sorter @required

 * @property Item $item @temp
 */
class Menu extends View
{
    protected function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'items_': return $this->getItems();
            case 'module': return $osm_app->modules['Osm_Ui_Menus'];
            case 'item_types': return $this->module->item_types;
            case 'sorter': return $osm_app[Sorter::class];
            case 'items_can_be_checked': return $this->canItemsBeChecked();
        }
        return parent::default($property);
    }

    protected function getItems() {
        $result = [];

        foreach ($this->items as $name => $data) {
            $data = $this->prepareItemData($data);
            $result[$name] = Item::new($data, $name, $this);
        }

        unset($this->items);
        $this->sorter->orderBy($result, 'sort_order');

        return $result;
    }

    protected function prepareItemData($data) {
        if (!isset($data['type'])) {
            throw new PropertyNotSet(m_("Menu item 'type' property is not set. Full item definition: :definition",
                ['definition' => json_encode($data)]));
        }

        if (!isset($data['class'])) {
            $data['class'] = $this->item_types[$data['type']]->model_class;
        }

        return $data;
    }

    protected function canItemsBeChecked() {
        foreach ($this->items_ as $item) {
            if ($this->canItemBeChecked($item)) {
                return true;
            }
        }
        return false;
    }

    protected function canItemBeChecked(Item $item) {
        return $item->icon || $item->checked !== null || $item->checkbox_group;
    }
}