<?php

namespace Osm\Ui\Menus\Views;

use Osm\Core\Exceptions\NotSupported;
use Osm\Core\Object_;
use Osm\Ui\Buttons\Views\Button;

/**
 * @property PopupMenu $mobile_menu @required
 * @property Button $show_more @required
 *
 * @property string $horizontal_align @required @part By default, 'left'
 */
class MenuBar extends Menu
{
    public $type = 'menu_bar';
    public $template = 'Osm_Ui_Menus.menu_bar.menu';
    public $view_model = 'Osm_Ui_Menus.MenuBar.Menu';

    protected function default($property) {
        switch ($property) {
            case 'show_more': return Button::new([
                'alias' => 'show_more',
                'icon' => '-menu',
            ]);
            case 'wrap_modifier': return "{$this->on_color_} {$this->color_}";
            case 'horizontal_align': return 'left';
        }

        return parent::default($property);
    }

    public function rendering() {
        $this->mobile_menu = PopupMenu::new([
            'alias' => 'mobile_menu',
            'items' => $this->cloneObjects($this->items),
        ]);

        parent::rendering();
    }

    protected function cloneObjects($source) {
        return array_map([$this, 'cloneObject'], $source);
    }

    protected function cloneObject(Object_ $source, Object_ $parent = null) {
        $class = get_class($source);
        $result = new $class();
        $result->parent = $parent;

        foreach ($source->iterateParts() as $key => $value) {
            $key = explode(' ', $key);

            if (!isset($key[1])) {
                $result->{$key[0]} = $value instanceof Object_
                    ? $this->cloneObject($value, $result)
                    : $value;
                continue;
            }

            if (!is_array($source->{$key[0]})) {
                throw new NotSupported(
                    "Cloning iterable view property is not supported");
            }

            if (!isset($result->{$key[0]})) {
                $result->{$key[0]} = [];
            }

            $result->{$key[0]}[$key[1]] = $value instanceof Object_
                ? $this->cloneObject($value, $result)
                : $value;
        }

        return $result;
    }
}