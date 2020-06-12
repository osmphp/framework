<?php

namespace Osm\Ui\Forms\Views;

use Osm\Data\Sheets\Search;
use Osm\Ui\Images\Views\Image;
use Osm\Ui\Menus\Views\CommandItem;
use Osm\Ui\Menus\Views\MenuBar;
use Osm\Ui\Menus\Views\UploadCommandItem;

/**
 * Constructor properties:
 *
 * @property int $width @required @part
 * @property int $height @required @part
 * @property string $path @part
 *
 * Type overrides"
 *
 * @property MenuBar $menu @part
 *
 * Computed properties:
 *
 * @property Image $image @required
 * @property ImagePlaceholder $placeholder @required
 */
class ImageField extends SectionField
{
    const DEFAULT_WIDTH = 160;
    const DEFAULT_HEIGHT = 160;

    public $view_model = 'Osm_Ui_Forms.ImageField';
    public $type = 'image';

    public function __construct($data = []) {
        parent::__construct($data);


        $this->items = [
            'image' => $this->layout->view($this, Image::new([
                'attributes' => [
                    'class' => 'form-section__image',
                ],
            ]), 'items', 'image'),
            'placeholder' => $this->layout->view($this, ImagePlaceholder::new([
                'image' => $this->layout->view($this, Image::new([
                    'attributes' => [
                        'class' => 'form-section__image',
                    ],
                ]), 'image'),
            ]), 'items', 'placeholder'),
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
            case 'width': return static::DEFAULT_WIDTH;
            case 'height': return static::DEFAULT_HEIGHT;
            case 'image': return $this->items['image'];
            case 'placeholder': return $this->items['placeholder'];
        }
        return parent::default($property);
    }

    public function assign($data) {
        $this->value = $data->{"{$this->name}__uid"};
        $this->model = [
            'value' => $this->value,
            'filename' => $data->{"{$this->name}__name"},
            'width' => $this->width,
            'height' => $this->height,
            'path' => $this->path,
        ];

        if ($this->value) {
            $this->menu->items['add']->hidden = true;
            $this->image->file_ = $data->{"{$this->name}__file"};
            $this->image->thumbnails = $data->{"{$this->name}__thumbnails"};
        }
        else {
            $this->menu->items['replace']->hidden = true;
            $this->menu->items['clear']->hidden = true;
        }

        return $this;
    }

    public function rendering() {
        parent::rendering();

        $this->image->width = $this->width;
        $this->image->height = $this->height;

        $this->placeholder->image->width = $this->width;
        $this->placeholder->image->height = $this->height;
    }

    public function fetch(Search $search) {
        $search->select([$this->name => [
            'width' => $this->width,
            'height' => $this->height,
        ]]);
    }
}