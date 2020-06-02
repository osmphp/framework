<?php

namespace Osm\Ui\Menus\Views;

use Osm\Framework\Views\View;

/**
 * @property string $type @required @part
 * @property bool $hidden @part
 * @property bool $main @part
 * @property bool $dangerous @part
 *
 * Style properties:
 *
 * @property string $button_color
 * @property string $button_on_color
 * @property bool $button_outlined
 *
 * More precise type spec:
 *
 * @property Menu $parent
 *
 * Properties for menu bar rendering:
 *
 * @property string $menu_item_template @required @part
 * @property string $menu_item_view_model @part
 */
abstract class Item extends View
{
    protected function default($property) {
        switch ($property) {
            case 'template':
                return str_replace('{menu_type}', $this->parent->type,
                    $this->menu_item_template);
            case 'view_model':
                return str_replace('{menu_type}',
                    studly_case($this->parent->type),
                    $this->menu_item_view_model);
            case 'model': return $this->hidden ? ['hidden' => true] : [];
            case 'button_color': return $this->getButtonColor();
            case 'button_on_color': return $this->getButtonOnColor();
            //case 'button_outlined': return $this->dangerous;
        }
        return parent::default($property);
    }

    protected function getButtonColor() {
        if ($this->dangerous) {
            return 'danger';
        }

        if (!$this->main) {
            return $this->parent->color;
        }

        return 'neutral';
    }

    protected function getButtonOnColor() {
        if (!$this->main) {
            return $this->parent->on_color;
        }

        if (!$this->parent->on_color) {
            return $this->parent->color ?: 'primary';
        }

        if (starts_with($this->parent->on_color, 'primary')) {
            return 'secondary' . substr($this->parent->on_color,
                strlen('primary'));
        }

        return 'primary' . substr($this->parent->on_color,
            strlen('secondary'));
    }
}