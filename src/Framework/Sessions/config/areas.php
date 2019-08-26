<?php

use Osm\Framework\Sessions\Advices\StartSession;

return [
    'web' => [
        'resource_path' => 'resources',
        'advices' => [
            'start_session' => ['class' => StartSession::class, 'sort_order' => 10],
        ],
    ],
    'api' => [

    ],
];