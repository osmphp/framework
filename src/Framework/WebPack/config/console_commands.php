<?php

use Osm\Framework\WebPack\Commands;
use Symfony\Component\Console\Input\InputOption;

return [
    'config:webpack' => [
        'description' => osm_t("Updates WebPack configuration"),
        'class' => Commands\ConfigWebPack::class,
        'options' => [
            'all' => [
                'type' => InputOption::VALUE_NONE,
                'description' => osm_t("Target all themes in all areas, not just current ones"),
            ],
        ],
    ],
];