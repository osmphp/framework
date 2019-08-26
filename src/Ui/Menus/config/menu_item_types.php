<?php

use Osm\Ui\Menus\Items;
use Osm\Ui\Menus\Items\Type;

return [
    // item types with only basic set of properties
    Type::SEPARATOR => [],
    Type::PLACEHOLDER => [],

    // item types below are named: may have title and icon
    Type::LABEL => [],

    // item types below are interactive: may be enabled/disabled, checked/unchecked, may belong to checkbox group
    Type::SUBMENU => [],
    Type::INPUT => [],

    // item types below may have keyboard shortcuts
    Type::COMMAND => [],
    Type::LINK => [],
];