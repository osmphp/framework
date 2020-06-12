<?php
/* @var \Osm\Ui\Lists\Views\List_ $view */

use Osm\Ui\Forms\Views\Tab;
use Osm\Ui\Forms\Views\Section;
use Osm\Ui\Forms\Views\CheckboxField;
use Osm\Ui\Forms\Views\DropdownField;
use Osm\Ui\Forms\Views\ImageField;
use Osm\Ui\Forms\Views\PriceField;
use Osm\Ui\Forms\Views\StringField;
use Osm\Framework\Views\Views\Container;

/* @var object|\Osm\Samples\Ui\Hints\ContactHint $item */
$item = $view->item;
?>
@include (Container::new([
    'alias' => "contact__{$item->id}",
    'items' => [
        'selected' => CheckboxField::new([
            'name' => 'selected',
            'prefix' => "{$item->id}__",
            'title' => $item->name,
            'path' => 't_contacts',
            'sort_order' => 0,
            'value' => false,
        ]),
        'image' => ImageField::new([
            'name' => 'image',
            'prefix' => "{$item->id}__",
            'title' => osm_t("Photo"),
            'path' => 't_contacts',
            'sort_order' => 10,
        ])->assign($item),
        'info' => Section::new([
            'title' => osm_t("Info"),
            'sort_order' => 20,
            'items' => [
                'name' => StringField::new([
                    'name' => 'name',
                    'prefix' => "{$item->id}__",
                    'title' => osm_t("Name"),
                    'required' => true,
                    'autocomplete' => 'off',
                    'sort_order' => 10,
                ])->assign($item),
                'group' => DropdownField::new([
                    'name' => 'group',
                    'prefix' => "{$item->id}__",
                    'title' => osm_t("Group"),
                    'option_list' => 't_contact_groups',
                    'sort_order' => 20,
                ])->assign($item),
                'salary' => PriceField::new([
                    'name' => 'salary',
                    'prefix' => "{$item->id}__",
                    'title' => osm_t("Salary"),
                    'autocomplete' => 'off',
                    'sort_order' => 30,
                ])->assign($item),
                'phone' => StringField::new([
                    'name' => 'phone',
                    'prefix' => "{$item->id}__",
                    'title' => osm_t("Phone"),
                    'required' => true,
                    'autocomplete' => 'off',
                    'sort_order' => 40,
                ])->assign($item),
                'email' => StringField::new([
                    'name' => 'email',
                    'prefix' => "{$item->id}__",
                    'title' => osm_t("Email"),
                    'required' => true,
                    'autocomplete' => 'off',
                    'sort_order' => 50,
                ])->assign($item),
            ],
        ]),
    ],
]))

