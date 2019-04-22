<?php

use Manadev\Data\OptionLists;

return [
    'dummy' => ['class' => OptionLists\Dummy::class, 'title' => m_("Dummy")],
    'yes_no' => ['class' => OptionLists\YesNo::class, 'title' => m_("Yes / No")],
    'areas' => ['class' => OptionLists\Areas::class, 'title' => m_("Areas")],
];