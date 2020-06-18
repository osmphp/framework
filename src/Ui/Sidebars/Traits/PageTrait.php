<?php

namespace Osm\Ui\Sidebars\Traits;

use Osm\Framework\Views\Views\Main;
use Osm\Framework\Views\Views\Page;

trait PageTrait
{
    protected function around_default(callable $proceed, $property) {
        $view = $this; /* @var Page $view */

        $result = $proceed($property);

        if ($property != 'modifier_') {
            return $result;
        }

        /* @var Main $main */
        if (!($main = $view->items['main'] ?? null)) {
            return $result;
        }

        if (!$main->items['sidebar']->empty) {
            $result .= ' -has-sidebar';
        }

        if (!$main->items['alternative_sidebar']->empty) {
            $result .= ' -has-alternative-sidebar';
        }

        return $result;
    }
}