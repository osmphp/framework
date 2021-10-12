<?php

declare(strict_types=1);

return (object)[
    /* @see \Osm\Framework\Logs\Hints\LogSettings */
    'logs' => (object)[
        'db' => (bool)($_ENV['LOG_DB'] ?? false),
    ],
];