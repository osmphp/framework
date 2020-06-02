<?php

namespace Osm\Ui\Forms\Views;

use Osm\Ui\Menus\Views\CommandItem;
use Osm\Ui\Menus\Views\MenuBar;
use Osm\Ui\Menus\Views\UploadCommandItem;

class ImageField extends Section
{
    public $view_model = 'Osm_Ui_Forms.ImageField';
    public $type = 'image';

    public function __construct($data = []) {
        parent::__construct($data);

        $this->modifier = '-empty';

        $this->items = [
            'value' => $this->layout->view($this, ImageValue::new([

            ]), 'items', 'value'),
        ];

        $this->menu = $this->layout->view($this, MenuBar::new([
            'horizontal_align' => 'right',
            'items' => [
                'add' => UploadCommandItem::new([
                    'title' => osm_t("Add"),
                    'accept' => 'image/*',
                    'route' => 'POST /forms/upload-image',
                    'sort_order' => 10,
                ]),
                'replace' => UploadCommandItem::new([
                    'title' => osm_t("Replace"),
                    'accept' => 'image/*',
                    'route' => 'POST /forms/upload-image',
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
}