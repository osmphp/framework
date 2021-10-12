<?php

declare(strict_types=1);

return (object)[
    /* @see \Osm\Framework\Logs\Hints\LogSettings */
    'logs' => (object)[
        'elastic' => (bool)($_ENV['LOG_ELASTIC'] ?? false),
    ],
];