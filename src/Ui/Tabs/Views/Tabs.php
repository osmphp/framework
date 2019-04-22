<?php

namespace Manadev\Ui\Tabs\Views;

use Manadev\Framework\Views\Views\Container;
use Manadev\Ui\Tabs\Tab;

/**
 * @property array $tabs @required @part
 * @property Tab[] $tabs_ @required
 */
class Tabs extends Container
{
    public $template = 'Manadev_Ui_Tabs.tabs';

    protected function default($property) {
        switch ($property) {
            case 'tabs_': return $this->getTabs();
            case 'views': return $this->getViews();
        }
        return parent::default($property);
    }

    protected function getViews() {
        return array_map(function(Tab $tab) {
            return $tab->view;
        }, $this->tabs_);
    }

    protected function getTabs() {
        $result = [];

        foreach ($this->tabs as $name => $data) {
            $result[$name] = Tab::new($data, $name, $this);
        }

        return $result;
    }
}