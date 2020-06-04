<?php

namespace Osm\Ui\Forms\Views;

use Osm\Ui\Menus\Views\CommandItem;
use Osm\Ui\Menus\Views\MenuBar;
use Osm\Ui\Menus\Views\UploadCommandItem;

/**
 * @property MenuBar $menu @part
 * @property string $url
 *
 * Computed properties:
 *
 * @property ImageValue $value_view @required
 */
class ImageField extends SectionField
{
    public $view_model = 'Osm_Ui_Forms.ImageField';
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

    protected function default($property) {
        switch ($property) {
            case 'value_view': return $this->items['value'];
        }
        return parent::default($property);
    }

    public function assign($data) {
        $this->value = $data->{"{$this->name}__uid"};
        $this->model = [
            'value' => $this->value,
            'filename' => $data->{"{$this->name}__name"},
        ];
        if ($this->value) {
            $this->url = $data->{"{$this->name}__url"};
        }
    }

    public function rendering() {
        parent::rendering();

        if ($this->value) {
            $this->value_view->url = $this->url;
            $this->menu->items['add']->hidden = true;
        }
        else {
            $this->state = '-empty';
            $this->menu->items['replace']->hidden = true;
            $this->menu->items['clear']->hidden = true;
        }
    }
}