<?php

namespace Manadev\Data\OptionLists;

use Manadev\Data\OptionLists\Hints\OptionHint;

/**
 * @property OptionHint[] $items @required @part Explicitly mark as cache-able
 */
class YesNo extends OptionList
{
    protected function all() {
        return collect([
            0 => (object)['title' => m_("No")],
            1 => (object)['title' => m_("Yes")],
        ]);
    }
}