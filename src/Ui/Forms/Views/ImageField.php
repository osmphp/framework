<?php

namespace Osm\Ui\Forms\Views;

use Osm\Ui\Menus\Views\CommandItem;
use Osm\Ui\Menus\Views\MenuBar;

class ImageField extends Section
{
    public $type = 'image';

    public function __construct($data = []) {
        parent::__construct($data);

        $this->items = [
            'value' => $this->layout->view($this, ImageValue::new([

            ]), 'items', 'value'),
        ];

        $this->menu = $this->layout->view($this, MenuBar::new([
            'horizontal_align' => 'right',
            'items' => [
                'add' => CommandItem::new([
                    'title' => osm_t("Add"),
                    'sort_order' => 10,
                ]),
                'replace' => CommandItem::new([
                    'title' => osm_t("Replace"),
                    'sort_order' => 20,
                ]),
                'clear' => CommandItem::new([
                    'title' => osm_t("Clear"),
                    'dangerous' => true,
                    'sort_order' => 30,
                ]),
            ],
        ]), 'menu');
    }
//    public $view_model = 'Osm_Ui_Forms.ImageField';
}