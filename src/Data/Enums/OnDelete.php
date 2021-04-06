<?php

declare(strict_types=1);

namespace Osm\Framework\Data\Enums;

class OnDelete
{
    const CASCADE = 'cascade';
    const RESTRICT = 'restrict';
    const SET_NULL = null;
}