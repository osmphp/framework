<?php

use Osm\Framework\Composer\Commands;
use Symfony\Component\Console\Input\InputArgument;

return [
    'composer-hooks' => [
        'description' => osm_t("Runs Composer hooks"),
        'class' => Commands\RunHooks::class,
        'arguments' => [
            'event' => [
                'type' => InputArgument::REQUIRED,
                'description' => osm_t("Composer event hooks are registered to"),
            ],
        ],
    ],
];