<?php

use Osm\Framework\Sessions\Advices\StartSession;

return [
    'web' => [
        'advices' => [
            'start_session' => ['class' => StartSession::class, 'sort_order' => 10],
        ],
    ],
];