<?php

namespace Manadev\Data\OptionLists;

use Illuminate\Support\Collection;

class Dummy extends OptionList
{
    protected function collectionLookup(Collection $keys) {
        $result = [];
        foreach ($keys as $key) {
            $result[$key] = (object)['title' => $key];
        }
        return collect($result);
    }
}