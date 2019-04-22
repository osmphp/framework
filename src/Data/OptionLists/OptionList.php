<?php

namespace Manadev\Data\OptionLists;

use Illuminate\Support\Collection;
use Manadev\Core\Exceptions\NotSupported;
use Manadev\Core\Object_;
use Manadev\Data\OptionLists\Hints\OptionHint;

/**
 * @property string $name @required @part
 * @property bool $supports_db_queries @part
 * @property Collection|OptionHint[] $items @required Not part as it is often too dynamic to be stored in cache
 */
abstract class OptionList extends Object_
{
    protected function default($property) {
        switch ($property) {
            case 'items': return $this->all();
        }
        return parent::default($property);
    }

    /**
     * Adds option data to given collection.
     *
     * @param Collection $collection
     * @param string $key $key property in collection items should contain option key.
     *      If $key argument is omitted, collection items are expected to have 'value'
     *      property containing option key.
     * @param null $data In most cases, option data is just 'title', but some option lists
     *      may contain more data columns (for instance, SEO URL keys). Optional $data
     *      argument specifies which data columns should be added to collection items using which names.
     *      If $dataMappings argument is omitted, all data columns are added under their original names,
     *      that is, for most option lists, 'title' column is added containing option title
     */
    public function addToCollection($collection, $key = 'value', $data = null) {
        if (empty($collection)) {
            return;
        }

        $optionData = $this->collectionLookup($collection->pluck($key));

        foreach ($collection as $item) {
            if (!isset($optionData[$item->$key])) {
                continue;
            }

            foreach ($optionData[$item->$key] as $property => $value) {
                if ($property == 'value') {
                    continue;
                }

                if ($data) {
                    if (!isset($data[$property])) {
                        continue;
                    }
                    $property = $data[$property];
                }

                $item->{$property} = $value;
            }
        }
    }

    public function addToQuery($query, $key = 'value', $data = null) {
        throw new NotSupported();
    }

    protected function all() {
        return collect();
    }

    /**
     * Returns options having specified keys. This method is used in adding option data to collection to avoid
     * database lookups for every single collection item
     *
     * @param Collection $keys
     * @return Collection
     */
    protected function collectionLookup(Collection $keys) {
        // in array-based option lists, lookup happen on the whole option list array
        return $this->all();
    }

    public function offsetGet($offset) {
        return $this->items[$offset];
    }
}