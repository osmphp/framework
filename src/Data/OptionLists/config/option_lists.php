<?php

use Osm\Data\OptionLists;

return [
    'dummy' => ['class' => OptionLists\Dummy::class, 'title' => osm_t("Dummy")],
    'yes_no' => ['class' => OptionLists\YesNo::class, 'title' => osm_t("Yes / No")],
    'areas' => ['class' => OptionLists\Areas::class, 'title' => osm_t("Areas")],
];