<?php

use Osm\Framework\Http\Advices;

return [
    'detect_area' => ['class' => Advices\DetectArea::class, 'sort_order' => 30],
    'redirect_to_trailing_slash' => ['class' => Advices\RedirectToTrailingSlash::class, 'sort_order' => 40],
    'detect_route' => ['class' => Advices\DetectRoute::class, 'sort_order' => 50],
];