<?php

namespace Osm\Samples\Ui\OptionLists;

use Osm\Data\OptionLists\OptionList;

class ContactGroups extends OptionList
{
    protected function all() {
        return collect([
            'work' => (object)['title' => osm_t("Work")],
            'friends' => (object)['title' => osm_t("Friends")],
            'other' => (object)['title' => osm_t("Other")],
        ]);
    }
}